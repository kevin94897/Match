# Match — tema de WordPress

Tema del sitio de [Match](https://match.win) (consultora de headhunting ejecutivo, Perú). Implementa la identidad visual del archivo de Figma y las plantillas del Job Board, que se alimenta del plugin **Match Job Board** conectado al CRM.

- **Requiere:** WordPress 6.4+, PHP 8.0+.
- **Plugin recomendado:** `match-job-board` (vacantes, filtros y postulaciones). El tema funciona sin él: toda plantilla de vacantes comprueba `match_job_board_active()` antes de pintar.
- **Diseño:** Figma `RtxC4dWQJzTdKWdkVqXm76` — Home `3502:3318`, internas Executive `3232:4955` y Outplacement `3280:450`.

## Instalación

1. Copiar la carpeta a `wp-content/themes/match-theme/` y activar el tema.
2. Activar el plugin `match-job-board` y sincronizar vacantes desde su pantalla de ajustes.
3. Crear las páginas:
   - **Inicio** (asignarla como portada estática) → `front-page.php`.
   - **Soluciones** → hijas **Executive** y **Outplacement** con la plantilla *Solución*.
   - **Design System** con la plantilla *Design System* (guía visual de tokens y componentes; opcional).
4. Asignar menús en Apariencia → Menús: `primary`, `footer_jobs`, `footer_nav`, `footer_social`. Sin menús asignados el tema pinta los ítems del diseño.
5. Personalizador → **Match — Contacto**: teléfono, correo, RUC y razón social (pie y formulario).

Sin build en producción: se sirven los archivos compilados que van en el repo (`assets/css/tailwind.css`, `assets/vendor/`). Para desarrollar:

```
npm install
npm run build   # compila Tailwind y copia AOS/Embla/Lenis a assets/vendor
npm run watch   # recompila Tailwind al guardar
```

## Estructura

```
style.css                 Cabecera del tema (los estilos viven en assets/css)
functions.php             Soportes, encolado, personalizador, formulario de contacto
inc/template-tags.php     Helpers: match_icon(), match_logo(), menús, datos de vacantes
inc/soluciones-data.php   Contenido de las internas de solución, por slug
header.php / footer.php   Abren y cierran el documento; la UI vive en template-parts/
front-page.php            Portada
template-solucion.php     Plantilla "Solución" (Executive, Outplacement…)
template-jobboard.php     Plantilla "Job Board" (panel)
template-login.php        Plantilla "Job Board — Login"
template-profile.php      Plantilla "Job Board — Perfil"
template-procesos.php     Plantilla "Job Board — Mis procesos"
template-guardados.php    Plantilla "Job Board — Guardados"
header-jobboard.php / footer-jobboard.php   Documento del Job Board, sin barra ni pie del sitio
inc/jobboard.php          Login (hooks sobre wp-login.php), URLs y datos del panel
template-design-system.php
package.json, scripts/    Build de Tailwind y copia de vendors (npm run build)
archive-match_job.php, single-match_job.php   Listado y detalle de vacante
template-parts/
  header/navbar.php       Barra de navegación (submenú, versión móvil)
  footer/footer.php       Pie de página
  hero.php, section-*.php Secciones de la portada
  job-row.php             Fila de vacante expandible de la portada
  solucion/*.php          Secciones de la interna de solución
  jobboard/*.php          Barra lateral, tarjeta de vacante y caja de CV del panel
assets/
  css/                    Ver "CSS" abajo
  js/app.js               Vanilla JS: menú móvil, acordeón, filas, AOS, Embla, Lenis, toast
  vendor/                 AOS, Embla y Lenis copiados por `npm run vendor`
  css/tailwind.src.css    Entrada de Tailwind (tokens con @theme inline)
  fonts/                  Poppins y Open Sauce (SIL OFL), servidas localmente
  img/                    Logo, iconos (currentColor), fotos WebP, logos de clientes
```

## CSS

Orden de carga (todo en `assets/css/`):

| Archivo | Contenido |
|---|---|
| `fonts.css` | `@font-face` |
| `tokens.css` | Variables `--match-*`: color, spacing, radios, tipografía. **Única fuente de verdad**; el plugin del Job Board consume estos mismos nombres, no renombrar. |
| `base.css` | Reset, titulares, eyebrow, layout de secciones, utilidades tipográficas |
| `components.css` | Botones, pills, tarjetas, logo de empresa, detalle de vacante, contenido editorial |
| `home.css` | Secciones de la portada y la "pestaña" del navbar sobre el hero |
| `header.css` / `footer.css` | Barra y pie |
| `solucion.css` | Interna de solución (solo con esa plantilla) |
| `jobboard.css` | Login y panel del Job Board (solo con esas plantillas) |
| `styleguide.css` | Página Design System (solo con esa plantilla) |
| `vendor/aos.css` | Animaciones de entrada (AOS) |
| `vendor/lenis.css` | Scroll suave (Lenis) |
| `tailwind.css` | Utilidades Tailwind v4, **compilado** desde `tailwind.src.css`; siempre al final |

Convención BEM con prefijo `match-`. Los iconos SVG se inyectan inline con `match_icon( 'nombre' )` y heredan `currentColor`.

### Tailwind (adopción progresiva)

- `assets/css/tailwind.src.css` importa solo `theme` y `utilities` (sin preflight: el reset es el de `base.css`) y expone los tokens `--match-*` con `@theme inline`, así `bg-primario-500`, `rounded-16` o `font-display` resuelven a las mismas variables.
- Las utilidades se importan **sin `@layer`** y `tailwind.css` se encola al final: así una utilidad le gana al CSS BEM existente a igual especificidad. No cambiar ese orden.
- Breakpoints alineados a las media queries del tema: `sm` ≥ 601 px, `md` ≥ 1025 px, `lg` ≥ 1441 px.
- Lo nuevo se escribe con utilidades en la plantilla; las secciones existentes se migran una por una cuando se tocan.

### Animaciones, carruseles y scroll

- **AOS**: `data-aos="fade-up"` (+ `data-aos-delay` para escalonar) en los bloques de cada sección; se inicializa en `app.js` con `once: true` y se desactiva con `prefers-reduced-motion`.
- **Lenis**: scroll suave sobre el scroll nativo (`autoRaf`, `lerp: 0.1`, anclas gestionadas en app.js con `offsetTop` (AOS desplaza las secciones no animadas)). Se omite con `prefers-reduced-motion` y se pausa con `lenis.stop()` mientras el menú móvil está abierto. Para excluir un contenedor con scroll propio, `data-lenis-prevent`.
- **Embla**: cualquier `[data-embla]` con un `[data-embla-viewport]` se convierte en carrusel. Opcionales: `[data-embla-prev]`, `[data-embla-next]`, `ul[data-embla-dots]` (los puntos se generan según los snaps reales) y `data-embla-options` con JSON de Embla. Usado en el diferenciador y los testimonios de la interna.

## Contenido editorial

Los textos de la portada y de las internas están en las plantillas y en `inc/soluciones-data.php` mientras no exista un CPT o campos personalizados. Para agregar una solución: crear la página hija de *Soluciones* con la plantilla *Solución* y añadir su bloque en `match_solucion_data()` con la misma clave que el slug. La clave `theme` define la paleta: `template-solucion.php` la pone como clase `match-sol--{theme}` en `<body>` y `solucion.css` redefine ahí las variables `--sol-*` (Executive es la base; Outplacement cambia a celeste). Los assets propios de cada solución van en `assets/img/{slug}/`.

Vacantes en la portada: `match_jobs_list()` (`inc/jobs-filters.php`) trae las últimas del Job Board con los filtros de la URL. Sin vacantes sincronizadas (o sin plugin) entra el **modo demo**: seis filas de ejemplo (`match_demo_jobs()`) con valores de filtro propios, y lista y facetas se calculan en memoria, así los filtros también se pueden revisar sin CRM.

### Filtros de vacantes (portada)

- Los selects consultan `GET /wp-json/match/v1/jobs?ubicacion=&nivel=&modalidad=&industria=` y `app.js` reemplaza filas, conteo, enlace "Ver todas" y opciones sin recargar. La URL se actualiza (`replaceState`) para poder compartirla; el servidor la respeta al renderizar.
- Conteos **facetados**: cada opción muestra cuántas vacantes quedarían al elegirla con los demás filtros activos, así nunca se ofrece una combinación vacía. Una combinación vacía solo llega por URL y muestra el estado vacío con "Borrar filtros". El botón "Borrar filtros" de la barra (y su separador) solo existe cuando hay filtros activos.
- Sin JavaScript el formulario envía al archivo `/vacantes/` con los mismos parámetros. Si `fetch` falla, también.
- Las filas se despliegan con `grid-template-rows` (`.match-job__reveal`) y el botón +/− gira; el toggle está delegado en `document` porque las filas se reemplazan.

## Job Board: login y panel

Páginas (crearlas en el admin; `inc/jobboard.php` las localiza por plantilla):

- **Job Board** → plantilla *Job Board* (`template-jobboard.php`, Figma 4529:3). Barra lateral, hero con caja de CV, vacantes destacadas y áreas por industria. Datos de `inc/jobs-filters.php` (modo demo sin CRM).
- **Iniciar sesión** (hija de Job Board) → plantilla *Job Board — Login* (`template-login.php`, Figma 4308:3581).

Ambas usan `header-jobboard.php` / `footer-jobboard.php` (documento sin la barra ni el pie del sitio) y `assets/css/jobboard.css`.

Flujo de acceso (todo en `inc/jobboard.php`, sobre el login nativo):

- `wp_login_url()` devuelve la página de login del tema, así el navbar y el plugin apuntan al diseño. `wp-login.php` por GET sin acción redirige allí; `lostpassword`, `register` y `logout` siguen en `wp-login.php`.
- El formulario envía a `wp-login.php`. Credenciales incorrectas vuelven con `?login=failed`; al entrar se redirige al panel (`login_redirect`), salvo administradores. Un usuario identificado que abra el login va al panel.
- Por ahora la barra de administración está oculta para todos en el frontend (`match_hide_admin_bar`); la condición original para editores queda en el comentario.
- "Continuar con Google" queda deshabilitado hasta conectar un proveedor OAuth: devolver la URL con el filtro `match/google_login_url`.
- **Modal de bienvenida** (`template-parts/jobboard/onboarding.php`, Figma 4409:159): se abre una sola vez por usuario al entrar al panel. Al cerrarlo, `app.js` avisa por AJAX (`match_onboarding_done`) y queda en user meta `match_onboarding_done`; `?onboarding=1` lo fuerza para revisarlo. "Completar perfil" apunta al perfil nativo hasta que exista la página de perfil (filtro `match/profile_url`).
- "Regístrate ahora" (login) y "Registrarse" (sidebar sin sesión) apuntan a `/job-board/registro/` si esa página existe (`match_registro_page_url()`), si no al formulario de contacto — igual que antes, mismo fallback.

**Registro en dos pasos** (`template-registro.php`, Figma 4341:3554; `template-registro-cv.php`, Figma 4462:3): mismo shell que el login (`.match-login-page*`, `.match-login-card`, fondo `login-bg.webp`, footer legal — reusado tal cual, sin CSS nuevo salvo el dropzone) para no duplicar nada.
- **Paso 1** solo pide correo. `match_handle_register()` (`inc/jobboard.php`) valida, rechaza si el correo ya tiene cuenta ("Ese correo ya tiene una cuenta. Inicia sesión."), crea el usuario (`wp_insert_user`, rol `subscriber`, contraseña aleatoria que nunca se expone), manda el correo nativo de WordPress para fijarla (`wp_new_user_notification`) y loguea automáticamente (`wp_set_auth_cookie`) para pasar directo al paso 2 sin pedir sesión de nuevo — el diseño no tiene pantalla intermedia de "revisa tu correo".
- **Paso 2** pide el CV con un dropzone (`.match-register-drop`, variante oscura del `.match-jb-drop` público) que reusa el mismo JS de `assets/js/app.js` (`[data-dropzone]`/`[data-dropzone-input]`/`[data-dropzone-hint]`, sin tocar código). "Continuar" sube el archivo con `match_handle_register_cv()` (mismo mecanismo que el CV del perfil: `MJB_CV::store()`, metas `mjb_cv_*`), termina en el panel. "Lo hago después" es un enlace directo al panel, sin backend — el CV nunca es obligatorio para tener cuenta.
- Ícono nuevo `assets/img/icons/upload-cv.svg` (bajado de Figma) para el dropzone: el `upload.svg` existente trae el círculo de fondo horneado en el mismo `currentColor` que el glifo, así que el glifo queda invisible sobre su propio fondo — bug preexistente en ese ícono, no tocado acá porque lo usa una vista distinta (`dropzone.php`, hero público).
- Las dos páginas son hijas de "Job Board" igual que el resto (`/job-board/registro/`, `/job-board/subir-cv/`).

Con sesión (Figma 4125:25) el panel muestra saludo y notificaciones en la topbar, la barra lateral completa (Inicio, Vacantes, Mis procesos, Guardados, Perfil, Cerrar sesión), las tarjetas "Explorar vacantes" y "Mi CV activo" y la sección "Guardado" (vacantes guardadas con el plugin; "Postulado" si ya hay postulación). 
- **Guardados** (hija de Job Board) → plantilla *Job Board — Guardados* (`template-guardados.php`, Figma 4282:3792). Exige sesión. Rejilla de dos columnas con las vacantes guardadas ("Guardado el {fecha}", pastilla "Postulado" si ya hay postulación) y chips Todas / Sin postular / Postuladas (`?estado=`). El marcador de cualquier tarjeta guarda o quita en vivo vía `POST /wp-json/match/v1/saved/{id}` (nonce REST; sin sesión lleva al login): los ids reales van al meta del plugin `mjb_saved_jobs` (compatible con `[match_saved_jobs]`) más `match_saved_dates`; los de ejemplo a `match_saved_demo`.

- **Mis procesos** (hija de Job Board) → plantilla *Job Board — Mis procesos* (`template-procesos.php`, Figma 4105:97; vacío 4406:3). Exige sesión. Lista las postulaciones del plugin (`MJB_Query::my_applications()`) con la etapa del meta `stage` (`sent`, `review`, `interview`, `final`, `closed` → pastillas Enviado, En revisión, Entrevista agendada, Decisión final, Finalizado) y orden por fecha (`?orden=reciente|antiguo`). Hoy el plugin solo escribe `sent`; las demás etapas llegarán cuando el CRM exponga el avance. En modo demo muestra cinco filas de ejemplo con todas las etapas (`?demo=0` para ver el estado vacío).

- **Perfil** (hija de Job Board) → plantilla *Job Board — Perfil* (`template-profile.php`, Figma 4216:3). Exige sesión. Tres formularios por `admin-post.php` (`inc/jobboard.php`): información de cuenta (correo, nombre, apellido → `wp_update_user`), CV (se guarda con `MJB_CV::store()` en la carpeta protegida del plugin y en los metas `mjb_cv_file/name/date` que usa el formulario de postulación; el anterior se borra) y "Enviar link de reseteo" (`retrieve_password()`, el correo nativo de WordPress). El estado vuelve en `?perfil=saved|cv|reset|error`.

- **Listado de vacantes** `/vacantes/` (`archive-match_job.php`, Figma 4009:3081): marco del Job Board con buscador, cuatro filtros con conteo facetado (pill activa en celeste), "Borrar filtros", orden y paginación, todo por GET (`q`, `ubicacion`, `nivel`, `modalidad`, `industria`, `orden`, `/page/N/`). Filas `template-parts/jobboard/job-row.php`; al final, "Llegaste al final de esta lista". Datos de `match_jobs_archive_query()` (modo demo en memoria).
- **Detalle de vacante** (Figma 4047:3): `single-match_job.php` pinta las vacantes reales con el marco del Job Board (barra lateral, topbar "Volver a Vacantes") usando `template-parts/jobboard/job-detail.php` y `match_job_detail_data()`. Cabecera con logo, chips, guardar y "Postular ahora" (ancla al formulario del plugin `[match_apply_form]`); columnas Descripción / Lo que ofrecemos y Resumen del rol / Skills / Requisitos (este último solo en demo: el CRM lo manda dentro de la descripción). En modo demo cada tarjeta enlaza a `/job-board/?vacante=demo-N`, que muestra el mismo detalle con contenido de ejemplo.
- **Modal de postulación** (no se pudo traer el nodo exacto de Figma 4158:4922 — la sesión de Figma estaba desconectada; el modal sigue el lenguaje visual del resto del panel en su lugar): `#postular` (el formulario del plugin, con sus clases `.mjb-*`) vive en el flujo normal de la página, así que funciona sin JavaScript. Con JS, "Postular ahora" traslada ese mismo nodo dentro de un `<dialog>` (`#match-apply-modal`) en vez de duplicarlo — evita IDs repetidos y un envío doble. `jobboard.css` restylea las clases `.mjb-*` dentro del modal (inputs, botón, avisos) para que calcen con el resto del sistema.
- **Modales de resultado** (Figma 4391:3, "Postulación confirmada", para el de éxito; no hay nodo de error en el archivo — se adaptó el mismo shell en rojo, con `assets/img/icons/error-circle.svg` nuevo junto al `check-circle.svg` que sí viene de Figma): `class-mjb-applications.php` redirige el POST del formulario con `?mjb_applied=1` o `?mjb_error=mensaje` en la URL de la vacante; `job-detail.php` lee esos GET y renderiza el `<dialog>` que corresponde (`#match-apply-success` / `#match-apply-error`, ambos `.match-jb-result-modal`), que se abre solo por JS y limpia esos parámetros de la URL para que un refresh no lo repita. Éxito muestra ícono de check verde, empresa/rol, "Postulaste el {fecha}" y un botón a Mis Procesos; error muestra ícono de X rojo y el mensaje real del plugin (ej. "Ya postulaste a esta vacante"), con "Reintentar" que cierra este modal y abre el de postulación de nuevo. Esto reemplazó el aviso plano (`.mjb-notice`) que antes reabría el modal de postulación sin más — ese aviso del plugin se sigue viendo dentro del formulario como respaldo sin JavaScript.
- **Detalle del proceso** (Figma 4158:4266 el patrón de apertura, 4158:4284 el contenido — ambos ya traídos con Figma reconectada): en cuanto `match_job_application_data()` (`inc/jobboard.php`) encuentra una postulación del usuario a esa vacante, el detalle deja de mostrar "Postular ahora" y el bloque `#postular` — en su lugar `match-jb-detail__actions` (la cabecera, junto al marcador y la pastilla de etapa) muestra "Ver proceso" (`match-jb__btn match-jb__btn--dark`, el mismo botón oscuro que usa "Postular ahora"; solo aparece si ya postuló, igual que la pastilla de etapa) que abre `#match-status-drawer`: un `<dialog>` nativo (fondo, foco y Esc de serie) anclado al borde derecho, alto completo, 540px, que entra deslizándose desde la derecha (`.match-jb-status-drawer`, `jobboard.css`). Adentro, calcado del nodo de Figma: eyebrow + empresa/rol, pastilla de etapa + fecha de postulación, "Etapas del proceso" (solo las ya alcanzadas, punto relleno azul marino + línea, la actual con anillo hueco — el plugin hoy no trae fecha por etapa, así que no se inventan fechas para las intermedias), "Próximo paso" (texto por etapa, nuevo campo `hint` en `match_application_stages()`) y "CV enviado" (reutiliza `match_user_cv()` y el `template-parts/jobboard/cv-row.php` que ya usa Perfil; no aparece en modo demo porque esa función exige plugin activo). Cierre circular estilo "Button-Secondary" de Figma, mismo trato que el botón de guardar. En mobile ocupa todo el ancho. Toda postulación nueva arranca en "Enviado" por defecto, igual que hoy en Mis procesos.

Pendiente: la caja "Déjanos tu CV" del panel público valida el archivo en el navegador (PDF/Word, 5 MB) pero el envío no está definido con el CRM.

Pulido transversal del Job Board (bloque final de `jobboard.css`): texto secundario en `--match-neutral-350` (5:1 sobre blanco; `#868686` no pasaba 4.5:1), anillos de foco coherentes, estados de carga (login, filtros), pulso del marcador al guardar, entrada escalonada de tarjetas, topbar fija, contadores en Mis procesos y Guardados, navegación con desplazamiento horizontal en móvil y barra de acciones fija en el detalle móvil.

## Formulario "Hablemos"

`template-parts/section-contacto.php` envía a `admin-post.php?action=match_contact`. `match_handle_contact()` valida nonce y honeypot, envía por `wp_mail` al correo del Personalizador y redirige con `?contacto=ok|error#contacto`.

## Verificación local

Sitio en Local: `http://match.local`. Para capturas con Chrome headless usar un ancho ≥ 600 px (Chrome impone un mínimo de ventana y a 430 px recorta el viewport).

## Licencia

GPL-2.0-or-later. Fuentes bajo SIL Open Font License (`assets/fonts/`).
