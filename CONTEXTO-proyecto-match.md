# Match — Job Board en WordPress. Contexto del proyecto

**Cliente:** Match (Match Consultores SAC) — consultora de headhunting ejecutivo, Perú
**Proyecto:** Sitio web en WordPress con Job Board integrado a Match Recruit CRM
**Plazo:** 25 días corridos (~17–18 hábiles)
**Última actualización:** 16 de septiembre de 2026

---

## 1. Qué se está construyendo

Un sitio en WordPress con dos piezas:

- **Plugin `match-job-board`** — sincroniza las vacantes del CRM, publica el listado y gestiona las postulaciones con envío de CV.
- **Tema `match-theme`** — identidad visual de Match, según el archivo de Figma (Home, nodo `3502:3318`).

El CRM es el sistema propietario de Match (`crm.match.win`), desarrollado por un tercero. Nosotros consumimos su API público.

---

## 2. Cronología de la revisión del API

La documentación cambió tres veces durante la revisión. Vale la pena tenerlo presente: **el equipo del CRM responde sobre lo que planea tener, no sobre lo publicado.** Conviene verificar siempre contra el API real antes de darse por enterado.

| Momento | Versión | Qué había |
|---|---|---|
| Primera revisión | 1.1.184 | Solo 3 endpoints visibles, sin schemas expandidos |
| Segunda revisión | 1.1.184 | Schemas completos. Sin `status`, sin `skills`, sin `published_at`, sin paginación |
| Respuesta del cliente | — | Afirmaron que esos campos "ya existen". No existían en la versión publicada |
| Tercera revisión | 1.1.186 | Los campos aparecieron. Se agregó además `offset` y se cambió la recomendación del CV |

---

## 3. El API hoy (v1.1.186)

Base: `https://crm.match.win/api`

### Endpoints

| Método | Ruta | Auth |
|---|---|---|
| `GET` | `/public/jobs` | — |
| `POST` | `/portals/webhook/job_portal` | `X-Match-Webhook-Secret` |
| `POST` | `/public/requisitions` | `X-Api-Key` |

### `GET /public/jobs`

Parámetros: `status` (`published` \| `all`), `limit` (máx. 200, default 100), `offset`, `since` (ISO 8601).

Respuesta: `{ items[], total, limit, offset }`. `total` es el total que cumple el filtro; se compara `offset + items.length` contra `total` para saber si quedan páginas.

Campos de cada item:

- `match_job_id` — **llave de correlación, no perderla nunca**
- `title`, `description` (HTML)
- `employment_type` — enum: `full_time`, `part_time`, `contract`, `internship`, `freelance`
- `work_mode` — enum: `onsite`, `remote`, `hybrid`
- `location` `{city, state, country}` (city y state nullable)
- `salary` `{disclosed, min, max, currency ISO 4217, period: month|year|hour|project}`
- `seniority` — **texto libre, no enum**
- `status` — enum: `published`, `closed`, `unpublished`
- `closed_at`, `openings_count`, `deadline`
- `skills[]` — **ver advertencia abajo**
- `published_at` (distinto de `updated_at`), `updated_at`
- `benefits` — HTML enriquecido
- `company` `{name, logo_url, website, industry}`
- `portal` `{post_id, post_url, synced_at}` — nullable

**Advertencias del propio schema:**

- El orden es `updated_at DESC`. Una edición durante el recorrido puede mover una vacante de página. Para sincronización incremental usar `since`, no `offset`.
- **`skills` viene vacío en las vacantes nuevas.** El CRM dejó de capturarlo por decisión de negocio; ahora el reclutador redacta las habilidades dentro de `description`. La doc dice textualmente: "diseñar la tarjeta para que se vea bien sin chips".

### `POST /portals/webhook/job_portal`

- `id` — UUID v4 generado por el portal. **Idempotencia**: si se reenvía el mismo `id`, el CRM responde `{ok: true, created: false}` sin duplicar.
- `job_id` — ID del post en WordPress
- `match_job_id` — enviar siempre que esté disponible
- `applicant` — `first_name` (máx. 80), `last_name`, `email`, `phone` (con código de país), `secondary_email`, `secondary_phone`, `cv_url`, `cover_letter` (máx. 2000)
- `applied_at` — ISO 8601

**CV:** el schema recomienda ahora **URL firmada de vida corta (10 minutos)** en lugar de pública y permanente, citando la Ley 29733. Requisitos: `https`, máx. 10 MB, hasta 3 redirecciones, `Content-Type` real. Formatos: PDF, DOC, DOCX, PNG, JPG, WEBP.

**Crítico:** si la descarga falla, **el CRM no reintenta**. Registra la postulación y guarda la URL tal como la recibió. Con un enlace expirado, esa postulación queda sin CV para siempre.

### `POST /public/requisitions`

Permite que sistemas externos soliciten vacantes nuevas. Campos: `client_id` (obligatorio), `title` (obligatorio), `description`, `contact_name`, `contact_email`, `employment_type`, `work_mode`, `country`, `city`, `seniority_level`, `years_exp_min/max`, `salary_min/max`, `salary_currency`, `openings_count`, `notes`.

---

## 4. Limitaciones y riesgos abiertos

### Bloqueante: integración directa CRM → WordPress

El cliente respondió que **no van a construir un endpoint de lectura de postulaciones** porque *"el CRM ya actualiza el WordPress directamente cuando hay un cambio de estado"*.

Esa integración **no está en ninguna documentación**. Hay un indicio de que existe: la doc menciona que las vacantes a dar de baja vienen "cada una con su `portal.post_id`, el id con el que despublicar el aviso del lado del portal" — o sea, el CRM conoce el ID del post de WordPress, y no hay endpoint documentado por el cual el portal se lo informe.

**Preguntas sin responder:**

1. Mecanismo de escritura (REST de WP, XML-RPC, plugin propio, acceso a BD)
2. Esquema exacto: post type, claves meta, taxonomías — para vacantes y postulaciones
3. Autenticación y credenciales
4. ¿Está operativa hoy? ¿Contra qué portal? (los ejemplos apuntan a `empleos.match.win`)
5. Fuente única de verdad: si el CRM crea los posts, nuestra sincronización no debe escribir en paralelo
6. Cómo se refleja el estado de una postulación en WordPress

**Se solicitó reunión técnica de 30 minutos. Sin resolverse.**

### Otras limitaciones

- **"Mis procesos"** — implementado con estado único "Enviada". Sin lectura de postulaciones no puede avanzar.
- **Panel de notificaciones** — de los 5 tipos del diseño solo 2 son alimentables. Fuera de fase 1.
- **`seniority` es texto libre** — el filtro "Nivel" se construye dinámicamente y normaliza mayúsculas, tildes y alias.
- **Sin `requirements`** — se fusiona dentro de `description`.
- **Sin flag `featured`** — "Vacantes destacadas" usa las más recientes.
- **Sin rate limits documentados**, ni respuestas 409/429.
- **Inconsistencia en la doc:** el error 400 del webhook menciona `applicant.full_name`, pero el schema define `first_name` y `last_name` por separado.
- **Contradicción en la doc:** el bloque de resumen sobre el webhook todavía dice "URL pública y permanente… sin autenticación", contradiciendo lo que ahora dice el campo `cv_url`.

---

## 5. Comunicaciones con el cliente

### Enviado

- **Documento de revisión técnica** (`revision-api-match-recruit-crm.md`) — auditoría completa con bloqueantes, campos faltantes, seguridad y priorización.
- **Correo formal** — con las tres definiciones requeridas: atención de requerimientos, decisión sobre el CV y definición de alcance sobre "Mis procesos".
- **WhatsApp al cliente** — 5 puntos técnicos con plazo.
- **WhatsApp al diseñador** — recortes, ajustes y faltantes.

### Respuesta del cliente

| Punto | Respuesta | Verificado |
|---|---|---|
| (a) Campo `status` | "Ya existe" + cerrar actualiza `updated_at` | Sí, en 1.1.186 |
| (b) `limit` máximo | 200, default 100 | Sí, y agregaron `offset` |
| (c) `skills` y `published_at` | "Ya están" | Sí, en 1.1.186 |
| (d) Lectura de postulaciones | **No procede. El CRM escribe directo en WP** | **Sin documentar** |
| (e) Descarga del CV | Inmediata | Sí, y cambiaron la recomendación |

---

## 6. Cambios acordados al diseño

Propuestos al diseñador para caber en el plazo:

**Recortes**
1. Eliminar el drawer "Detalle del proceso" con timeline de etapas
2. "Mis procesos" plana: lista, sin drawer, badge único "Enviada"
3. Eliminar el panel de notificaciones completo
4. Fusionar "Requisitos" dentro de "Descripción del puesto"
5. Consolidar las 3 variantes de tarjeta en 2

**Ajustes**
6. "Lo que ofrecemos" mantiene viñetas (`benefits` sí viene con formato HTML)
7. Fallback de monograma para logos: el CRM entrega favicons de 32–64 px para contenedores de 96
8. Filtro "Nivel" con cantidad variable de opciones
9. Contemplar salario por hora, año y proyecto, no solo `/mes`
10. "Vacantes destacadas" = las 3 más recientes
11. Añadir carta de presentación al modal de postulación (el API la soporta)

**Faltantes críticos del set de diseño**
12. **Vistas móviles** — las 8 vistas son desktop
13. **Login, registro, recuperar contraseña** — hay "Cerrar sesión" en el sidebar pero no existen esas pantallas
14. **Estados de carga y error** — solo hay un empty state

> Salida de emergencia si el móvil no llega: job board público sin cuenta en fase 1 (sin login, guardados, mis procesos ni perfil).

---

## 7. Código entregado

### `match-job-board` (plugin)

```
match-job-board.php          Bootstrap y constantes configurables
includes/
  class-mjb-helpers.php      Claves meta, formateo, normalización, log
  class-mjb-settings.php     Ajustes, con override por constantes
  class-mjb-post-types.php   CPT match_job, match_application + 7 taxonomías
  class-mjb-api.php          Cliente HTTP de los 3 endpoints
  class-mjb-mapper.php       item JSON → post + meta + términos
  class-mjb-sync.php         Carga inicial, incremental y reconciliación
  class-mjb-cv.php           Almacenamiento protegido y URLs firmadas
  class-mjb-applications.php Registro, envío idempotente y reintentos
  class-mjb-rest.php         Ruta firmada de descarga del CV
  class-mjb-query.php        Filtros del listado
  class-mjb-shortcodes.php   5 shortcodes del front
  class-mjb-admin.php        Ajustes, sincronización manual, log
templates/                   8 plantillas sobreescribibles desde el tema
assets/                      CSS y JS del front
```

**Decisiones de ingeniería relevantes:**

- **Post type detrás de constante.** `MJB_JOB_POST_TYPE` y `MJB_META_PREFIX`, más los filtros `mjb/job_post_type` y `mjb/meta_key`. Alinearlo con el esquema del CRM cuesta dos líneas, no una refactorización.
- **La URL firmada del CV se genera en cada intento de envío**, no al crear la postulación. Como el CRM no reintenta la descarga, generarla una sola vez dejaría sin CV cualquier postulación cuyo primer envío falle.
- **Segunda pasada en la carga inicial.** Se guarda la marca de tiempo antes de empezar, se pagina con desduplicado por `match_job_id`, y al terminar se lanza una consulta con `since` anterior al inicio. Sin eso se pierden vacantes de forma silenciosa cuando alguien edita durante el recorrido.
- **UUID persistido antes del primer envío**, lo que hace seguros los reintentos aunque el proceso muera a mitad.
- Reintentos con espera creciente: 2, 10 y 30 minutos, hasta 4 intentos, luego revisión manual con botón de reenvío.

**Shortcodes:** `[match_jobs]`, `[match_apply_form]`, `[match_my_applications]`, `[match_saved_jobs]`, `[match_requisition_form]`

### `match-theme` (tema)

```
style.css, functions.php, index.php, page.php, single.php, 404.php, search.php
header.php, footer.php, front-page.php
archive-match_job.php, single-match_job.php
template-parts/  hero, section-vacantes, section-soluciones
inc/template-tags.php
assets/css/  tokens.css (Figma), base.css, components.css
assets/js/app.js
```

Tokens extraídos del Figma: paleta completa (primario `#0f0b59`, escala azul-neutral de 50 a 900, neutros), spacing 4→180, radios, y dos familias — Open Sauce Sans para titulares grandes, Poppins para el resto.

---

## 8. Pendientes

### Del CRM
- Contrato de la integración directa CRM → WordPress **(bloqueante)**
- Confirmar que `skills` vendrá vacío en vacantes nuevas
- Aclarar `full_name` vs `first_name`/`last_name`
- Corregir el bloque de resumen del webhook que contradice a `cv_url`
- Rate limits, respuestas 409/429, entorno de pruebas
- Confirmar política de reintentos de descarga y aceptación de expiración de 30 min

### De diseño
- Vistas móviles, login/registro, estados de carga y error
- Exportar assets a `assets/img/` y `assets/fonts/` (la tabla de nodos está en `ENTREGA-fase-1.md`)
- Secciones de portada sin montar: testimonios, métricas, contacto

### De desarrollo
- Páginas de cuenta con los shortcodes existentes
- JSON-LD `JobPosting` (sale casi completo con los campos ya guardados)
- `screenshot.png` del tema
- Pruebas con datos reales
- Correr `php -l` y PHPCS: el entorno de generación no tenía PHP disponible

---

## 9. Cronograma propuesto

| Días | Trabajo |
|---|---|
| 1–3 | Pedido al CRM, aprobación de recortes, CPT y taxonomías, mock del JSON |
| 4–10 | Motor de sincronización y plantillas de listado y detalle |
| 11–15 | Autenticación, postulación con CV, webhook, guardados, perfil |
| 16–20 | Responsive, estados de carga y error, SEO, ajuste visual |
| 21–25 | QA, pruebas con datos reales, buffer |

**El buffer de 5 días no es opcional.** Sincronizar contra un API con preguntas abiertas es exactamente el tipo de tarea que se desborda.

---

## 10. Riesgo principal

La integración directa CRM → WordPress es un componente no documentado, desarrollado por un tercero, del que depende una funcionalidad aprobada en el diseño, a menos de 20 días de la entrega. Según cómo resulte:

- **Bien:** "Mis procesos" vuelve al alcance completo, en tiempo real, sin polling.
- **Mal:** acoplamiento a un componente sobre el que no se tiene control ni visibilidad.

Mientras no se resuelva, **no congelar el post type ni los nombres de los campos meta**.
