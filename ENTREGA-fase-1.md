# Match — Job Board y tema. Entrega de avance

**Fecha:** 16 de septiembre de 2026
**Contra:** Match Recruit CRM Public API v1.1.186
**Diseño:** Figma — Home, nodo `3502:3318`

---

## 1. Qué se entrega

Dos piezas independientes. El tema funciona sin el plugin (todas las plantillas comprueban `match_job_board_active()` antes de pintar nada de vacantes), y el plugin funciona con cualquier tema.

```
match-job-board/     Plugin: sincronización con el CRM y postulaciones
match-theme/         Tema: identidad de Match y plantillas del Job Board
```

### Instalación

1. Copiar ambas carpetas a `wp-content/plugins/` y `wp-content/themes/`.
2. Activar el plugin y el tema.
3. En **Vacantes → Ajustes**, cargar la URL base del API y el secreto del webhook.
4. Pulsar **Probar conexión** y después **Sincronización completa**.

En producción, los secretos van en `wp-config.php` y no en la base de datos:

```php
define( 'MJB_API_BASE', 'https://crm.match.win/api' );
define( 'MJB_WEBHOOK_SECRET', '…' );
define( 'MJB_REQUISITIONS_KEY', '…' );
```

El plugin los detecta y bloquea los campos en el admin.

**En nginx** hay que añadir a mano el bloqueo del directorio de CVs, porque el `.htaccess` que genera el plugin solo cubre Apache:

```nginx
location ^~ /wp-content/uploads/mjb-cv/ { deny all; return 403; }
```

---

## 2. Lo que ya funciona

### Sincronización

- `GET /public/jobs` con `status=all`, `limit`, `offset` y `since`.
- **Carga inicial** paginada hasta agotar `total`, con desduplicado por `match_job_id`.
- **Pasada de cierre**: al terminar el recorrido completo se lanza una consulta con `since` anterior al inicio. Cubre el aviso de la documentación de que una edición durante el recorrido puede mover una vacante de página y hacer que se salte.
- **Incremental** cada 15 minutos vía WP-Cron, con 5 minutos de solape.
- **Estados**: `published` publica; `closed` y `unpublished` bajan el aviso (configurable), y el valor crudo se guarda en meta porque significan cosas distintas para el negocio.
- **Reconciliación**: las vacantes que desaparecen del CRM sin cerrarse se despublican y se marcan.
- Bloqueo por transient para que dos sincronizaciones no se pisen.
- Registro de las últimas 200 operaciones, visible en el admin.

### Postulaciones

- CPT propio con UUID v4 generado **antes** del primer envío, que es lo que hace seguros los reintentos: el CRM detecta el duplicado y responde `created: false`.
- Reintentos con espera creciente: 2, 10 y 30 minutos, hasta 4 intentos. Después queda marcada para revisión manual, con botón de reenvío en el listado.
- **La URL firmada del CV se genera en cada intento**, no al crear la postulación. Así un reintento tardío nunca llega con un enlace vencido, que es el riesgo que abre la documentación al decir que el CRM no reintenta la descarga.
- Validación: nombre obligatorio, y al menos correo o teléfono. Teléfono normalizado con código de país.
- Campos opcionales que el API admite y el diseño no contemplaba: carta de presentación, correo y teléfono secundarios.

### CVs

- Archivos fuera de la biblioteca de medios, en `uploads/mjb-cv/`, bloqueados a nivel de servidor.
- Nombre opaco (UUID + 8 bytes aleatorios): no lleva el nombre del candidato ni se puede adivinar.
- Descarga solo por ruta REST firmada con HMAC y expiración de 30 minutos por defecto.
- Formatos y tamaño según la documentación: PDF, DOC, DOCX, PNG, JPG, WEBP, hasta 10 MB.

### Front

- Listado con los cuatro filtros del diseño resueltos por taxonomía, con conteo.
- Nivel se construye dinámicamente y normaliza mayúsculas, tildes y alias (`Sr.` → `senior`), porque el API entrega `seniority` como texto libre.
- Detalle de vacante, formulario de postulación, guardados, mis procesos, y formulario de solicitud de vacante para empresas (`POST /public/requisitions`).
- Tarjetas con respaldo de monograma cuando falta el logo, y `object-fit: contain` para que los favicons de 32–64 px no se vean estirados en el contenedor de 96.
- El bloque de habilidades desaparece por completo cuando no hay skills, sin dejar hueco.

### Tema

- Tokens extraídos del Figma: paleta completa, escala de spacing (4→180), radios, y las dos familias tipográficas con sus roles.
- Navbar con menú móvil, hero, sección Vacantes sobre fondo oscuro, acordeón de soluciones, footer.
- Plantillas de archivo y detalle de vacante.
- Responsive, foco visible, `prefers-reduced-motion` respetado.

---

## 3. Lo que falta para cerrar

### Bloqueado por el CRM

**Integración directa CRM → WordPress.** Sigue sin contrato. Hasta tenerlo:

- El post type y el prefijo de meta están detrás de constantes (`MJB_JOB_POST_TYPE`, `MJB_META_PREFIX`) y de los filtros `mjb/job_post_type` y `mjb/meta_key`. Alinearlos con lo que escriba el CRM es cambiar una constante.
- **No definir todavía el esquema definitivo.** Si el CRM escribe en otra estructura, esa parte se rehace.
- Hay que decidir la fuente única de verdad: si el CRM crea los posts, la sincronización por `GET /public/jobs` debe pasar a solo lectura para no pisarse.

**"Mis procesos".** Implementado con estado único "Enviada". El meta `stage` ya existe y está listo para recibir la etapa real. Cuando exista `GET /public/applications` o un webhook saliente, se añade la línea de tiempo sin tocar nada más.

**Panel de notificaciones.** Fuera de alcance de fase 1.

### Assets de diseño pendientes

El tema los referencia pero hay que exportarlos de Figma a `match-theme/assets/img/` y `assets/fonts/`:

| Archivo | Nodo en Figma |
|---|---|
| `hero.jpg` | `3502:3321` — Hero Photo Mask |
| `wordmark-match.svg` | `3502:3328` — Wordmark |
| Iconos de filtros (ubicación, nivel, modalidad, industria) | `3711:6395` a `3711:6398` |
| Icono de guardar | `3711:6566` — Icon/Fav |
| Iconos de flecha, cerrar, más, menos | `3686:5805`, `3711:6447`, `3711:10072`, `3711:10114` |
| Visuales del acordeón (4) | `4609:4`, `4609:34`, `4609:61`, `4609:79` |
| `poppins-{regular,medium,semibold}.woff2` | — |
| `open-sauce-sans-bold.woff2` | — |

Mientras tanto, el icono de guardar usa un marcador en CSS y el resto son formas geométricas. **No los sustituí por SVG dibujados a mano** para que no se cuelen iconos que no son los del diseño.

### Pendiente de diseño

- Vistas móviles (las ocho del set son desktop).
- Login, registro y recuperación de contraseña.
- Estados de carga y de error.
- Secciones de portada aún no montadas: testimonios, métricas y contacto.

### Pendiente de desarrollo

- Páginas de cuenta: perfil, "Mi CV activo", guardados, mis procesos, usando los shortcodes ya disponibles.
- SEO de la vacante: `JobPosting` en JSON-LD, que con los campos que ya guardamos sale casi completo.
- Pruebas con datos reales del CRM.

---

## 4. Shortcodes disponibles

| Shortcode | Para qué |
|---|---|
| `[match_jobs limit="10" filtros="si"]` | Listado con filtros y paginación |
| `[match_apply_form job="123"]` | Formulario de postulación |
| `[match_my_applications]` | Mis procesos |
| `[match_saved_jobs]` | Vacantes guardadas |
| `[match_requisition_form]` | Solicitud de vacante para empresas |

## 5. Ganchos para extender

| Hook | Uso |
|---|---|
| `mjb/job_post_type`, `mjb/meta_key` | Alinear el esquema con el del CRM |
| `mjb/application_payload` | Modificar el payload antes de enviarlo |
| `mjb/job_synced` | Reaccionar a cada vacante sincronizada |
| `mjb/application_sent`, `mjb/application_abandoned` | Avisos internos |
| `mjb/seniority_aliases` | Añadir variantes de escritura de nivel |
| `mjb/allowed_html` | Ajustar el saneamiento del HTML del CRM |
| `mjb/default_country_code` | Cambiar el prefijo telefónico por defecto |

---

## 6. Nota sobre la verificación

El código no pasó por un linter de PHP: el entorno donde se generó no tiene el binario disponible. Antes de desplegar conviene correr `php -l` sobre los archivos y, si el equipo lo usa, PHPCS con el estándar de WordPress.
