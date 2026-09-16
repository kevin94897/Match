<?php
/**
 * Portada. Secciones en el orden del diseño (Figma: Home, node 3502:3318).
 */
defined( 'ABSPATH' ) || exit;

get_header();

get_template_part( 'template-parts/hero' );
get_template_part( 'template-parts/section', 'testimonios' );
get_template_part( 'template-parts/section', 'vacantes' );
get_template_part( 'template-parts/section', 'soluciones' );
get_template_part( 'template-parts/section', 'metricas' );
get_template_part( 'template-parts/section', 'contacto' );

get_footer();
