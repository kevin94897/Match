<?php
/**
 * Template Name: Solución
 *
 * Interna de solución (Figma: Executive, node 3232:4955). El contenido sale
 * de inc/soluciones-data.php según el slug de la página.
 */
defined( 'ABSPATH' ) || exit;

$solucion = match_solucion_data( get_post_field( 'post_name' ) ) ?? match_solucion_data( 'executive' );

get_header();

get_template_part( 'template-parts/solucion/hero', null, $solucion );
get_template_part( 'template-parts/solucion/metricas', null, $solucion );
get_template_part( 'template-parts/solucion/diferenciador', null, $solucion );
get_template_part( 'template-parts/solucion/perfiles', null, $solucion );
get_template_part( 'template-parts/solucion/clientes', null, $solucion );
get_template_part( 'template-parts/solucion/testimonios', null, $solucion );
get_template_part( 'template-parts/section', 'contacto', array( 'lead' => $solucion['contact_lead'], 'lead_muted' => '' ) );
get_template_part( 'template-parts/solucion/toast', null, $solucion );

get_footer();
