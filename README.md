# Match — tema de WordPress

Tema del sitio de [Match](https://match.win) (consultora de headhunting ejecutivo, Perú). Implementa la identidad visual del archivo de Figma y las plantillas del Job Board, que se alimenta del plugin **Match Job Board** conectado al CRM.

- **Requiere:** WordPress 6.4+, PHP 8.0+.
- **Plugin recomendado:** `match-job-board` (vacantes, filtros y postulaciones). El tema funciona sin él: toda plantilla de vacantes comprueba `match_job_board_active()` antes de pintar.
- **Diseño:** Figma `RtxC4dWQJzTdKWdkVqXm76` — Home `3502:3318`, interna Executive `3232:4955`.

## Instalación

1. Copiar la carpeta a `wp-content/themes/match-theme/` y activar el tema.
2. Activar el plugin `match-job-board` y sincronizar vacantes desde su pantalla de ajustes.
3. Crear las páginas:
   - **Inicio** (asignarla como portada estática) → `front-page.php`.
   - **Soluciones** → hija **Executive** con la plantilla *Solución*.
   - **Design System** con la plantilla *Design System* (guía visual de tokens y componentes; opcional).
4. Asignar menús en Apariencia → Menús: `primary`, `footer_jobs`, `footer_nav`, `footer_social`. Sin menús asignados el tema pinta los ítems del diseño.
5. Personalizador → **Match — Contacto**: teléfono, correo, RUC y razón social (pie y formulario).

Sin proceso de build: CSS y JS se sirven tal cual.

## Estructura

```
style.css                 Cabecera del tema (los estilos viven en assets/css)
functions.php             Soportes, encolado, personalizador, formulario de contacto
inc/template-tags.php     Helpers: match_icon(), match_logo(), menús, datos de vacantes
inc/soluciones-data.php   Contenido de las internas de solución, por slug
header.php / footer.php   Abren y cierran el documento; la UI vive en template-parts/
front-page.php            Portada
template-solucion.php     Plantilla "Solución" (Executive, Assessment…)
template-design-system.php
archive-match_job.php, single-match_job.php   Listado y detalle de vacante
template-parts/
  header/navbar.php       Barra de navegación (submenú, versión móvil)
  footer/footer.php       Pie de página
  hero.php, section-*.php Secciones de la portada
  job-row.php             Fila de vacante expandible de la portada
  solucion/*.php          Secciones de la interna de solución
assets/
  css/                    Ver "CSS" abajo
  js/app.js               Vanilla JS: menú móvil, acordeón, filas, carrusel, toast
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
| `styleguide.css` | Página Design System (solo con esa plantilla) |

Convención BEM con prefijo `match-`. Los iconos SVG se inyectan inline con `match_icon( 'nombre' )` y heredan `currentColor`.

## Contenido editorial

Los textos de la portada y de las internas están en las plantillas y en `inc/soluciones-data.php` mientras no exista un CPT o campos personalizados. Para agregar una solución: crear la página hija de *Soluciones* con la plantilla *Solución* y añadir su bloque en `match_solucion_data()` con la misma clave que el slug.

Vacantes en la portada: `match_home_jobs()` usa las últimas del Job Board; si no hay sincronización muestra las cuatro filas de ejemplo del diseño.

## Formulario "Hablemos"

`template-parts/section-contacto.php` envía a `admin-post.php?action=match_contact`. `match_handle_contact()` valida nonce y honeypot, envía por `wp_mail` al correo del Personalizador y redirige con `?contacto=ok|error#contacto`.

## Verificación local

Sitio en Local: `http://match.local`. Para capturas con Chrome headless usar un ancho ≥ 600 px (Chrome impone un mínimo de ventana y a 430 px recorta el viewport).

## Licencia

GPL-2.0-or-later. Fuentes bajo SIL Open Font License (`assets/fonts/`).
