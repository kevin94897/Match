<?php
/**
 * Contenido de la portada.
 *
 * Se edita en la página marcada como portada con el grupo PCF
 * "Portada — Contenido" (pcf-json/group_match_home.json). Los datos fijos de
 * match_home_data() (Figma: Home 3502:3318) son el respaldo mientras el campo
 * hero_lead esté vacío, y campo a campo cuando uno queda en blanco.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Textos y fotos del diseño.
 */
function match_home_data(): array {
	$img = fn( string $file ): string => get_theme_file_uri( 'assets/img/' . $file );

	$sol = fn( string $slug, string $kicker, string $title, string $text, string $overlay, bool $light = false ): array => array(
		'slug'    => $slug,
		'url'     => home_url( '/soluciones/' . $slug . '/' ),
		'kicker'  => $kicker,
		'title'   => $title,
		'text'    => $text,
		'photo'   => $img( 'solucion-' . $slug . '.webp' ),
		'overlay' => $overlay,
		'light'   => $light,
	);

	return array(
		'hero_lead'        => __( 'Sé parte de la base de datos ejecutiva', 'match' ),
		'hero_lead_strong' => __( 'más importante de la región', 'match' ),
		'hero_lead_2'      => __( 'Las empresas líderes nos confían sus búsquedas.', 'match' ),
		'hero_photo'       => $img( 'hero.webp' ),
		'hero_card_title'  => __( 'Da el primer paso', 'match' ),
		'hero_card_sub'    => __( 'Accede a las posiciones más exclusivas del mercado ejecutivo', 'match' ),
		'hero_card_cta'    => __( 'Encuentra tu próximo puesto', 'match' ),
		'hero_logos'       => array_keys( match_client_logos() ),

		't_eyebrow'    => __( 'Bienvenido a Match', 'match' ),
		't_title'      => __( 'Empresas que confiaron en Match,', 'match' ),
		't_title_2'    => __( 'y encontraron al talento que buscaban', 'match' ),
		't_photo'      => $img( 'testimonio-equipo.webp' ),
		't_cta_strong' => __( 'Contactemos', 'match' ),
		't_cta_text'   => __( 'Conversemos sobre cómo podemos atender tus requerimientos', 'match' ),
		't_cta_label'  => __( 'Contactar ahora', 'match' ),
		't_intro'      => __( 'No son solo palabras.', 'match' ),
		't_intro_2'    => __( 'Lo dicen las empresas que nos confiaron sus búsquedas más difíciles.', 'match' ),
		't_review'     => array(
			'quote'   => __( '“Tengo el mejor de los conceptos del servicio de Match”', 'match' ),
			'text'    => __( 'Es inclusive mucho mejor que otras consultoras transnacionales, tienen real conocimiento del mercado. Sus candidatos mejoraron mis expectativas referente a su competencia.', 'match' ),
			'name'    => 'Cristopher Cardamo',
			'role'    => __( 'Global Project Manager', 'match' ),
			'company' => __( 'Alfa Laval, Suecia', 'match' ),
			'avatar'  => $img( 'testimonio-avatar.png' ),
		),
		't_note_title' => __( 'Nuestros profesionales hoy trabajan en', 'match' ),
		't_note_text'  => __( 'Compañías líderes que confiaron sus posiciones clave a Match.', 'match' ),
		't_logos'      => array_values( array_diff( array_keys( match_client_logos() ), array( 'aenza' ) ) ),

		'v_eyebrow' => __( 'Vacantes activas', 'match' ),
		'v_title'   => __( 'Vacantes.', 'match' ),

		's_eyebrow'  => __( 'Nuestras soluciones', 'match' ),
		's_title'    => __( 'Conoce nuestras soluciones', 'match' ),
		's_title_2'  => __( 'y encuentra la que necesitas', 'match' ),
		'soluciones' => array(
			$sol( 'executive', __( 'Headhunting', 'match' ), __( 'Executive', 'match' ), __( 'Búsqueda y atracción de ejecutivos de primer nivel para posiciones de alta dirección. Identificamos perfiles que no están buscando, pero que sí deberías conocer.', 'match' ), 'rgba(15,11,89,0.81)' ),
			$sol( 'assessment', __( 'Evaluación', 'match' ), __( 'Assessment', 'match' ), __( 'Evaluamos competencias, potencial y ajuste cultural con metodologías propias para que cada decisión de talento se tome con evidencia.', 'match' ), 'rgba(255,255,255,0.56)', true ),
			$sol( 'outplacement', __( 'Transición', 'match' ), __( 'Outplacement', 'match' ), __( 'Acompañamos a los profesionales en su transición de carrera con un programa que protege la reputación de la empresa y el futuro de las personas.', 'match' ), 'rgba(15,11,89,0.47)' ),
			$sol( 'personnel', __( 'Selección', 'match' ), __( 'Personnel', 'match' ), __( 'Selección de mandos medios y posiciones especializadas con la misma rigurosidad de un proceso ejecutivo.', 'match' ), 'rgba(138,56,245,0.32)' ),
		),

		'm_eyebrow' => __( 'Resultados medibles', 'match' ),
		'm_title'   => __( 'La velocidad y precisión,', 'match' ),
		'm_title_2' => __( 'medidas en números reales', 'match' ),
		'm_hero'    => array(
			'photo' => $img( 'metricas-ejecutivo.webp' ),
			'title' => __( 'Velocidad de contratación', 'match' ),
			'sub'   => __( 'Nuestro récord end-to-end.', 'match' ),
			'value' => __( '1 DÍA', 'match' ),
			'text'  => __( 'Récord de contratación, desde la vacante hasta la oferta aceptada.', 'match' ),
		),
		'm_split'   => array(
			array( 'label' => __( 'Presentación de candidatos:', 'match' ), 'value' => __( '1 hora récord', 'match' ) ),
			array( 'label' => __( 'Garantías usadas por clientes:', 'match' ), 'value' => '00' ),
		),
		'm_gauge'   => array(
			'value' => '100%',
			'title' => __( 'Primera terna', 'match' ),
			'text'  => __( 'De nuestros candidatos contratados están en nuestra primera terna.', 'match' ),
		),
		'm_stats'   => array(
			array( 'value' => '91%', 'title' => __( 'HiPo o Top Performer', 'match' ), 'text' => __( 'De nuestros candidatos son considerados de alto potencial o desempeño sobresaliente.', 'match' ) ),
			array( 'value' => '00', 'title' => __( 'Rotación', 'match' ), 'text' => __( 'Nuestros candidatos tienen una permanencia promedio de tres años, ahorrando costos de selección.', 'match' ) ),
		),

		'c_lead'       => __( 'Cuéntanos qué necesitas', 'match' ),
		'c_lead_muted' => __( '— ya sea headhunting, evaluación de talento u outplacement.', 'match' ),
		'c_bullets'    => array(
			array( 'icon' => 'lightning', 'head' => __( 'Respuesta rápida.', 'match' ), 'text' => __( 'Si estás listo para encontrar al candidato ideal, nos encantaría conversar.', 'match' ) ),
			array( 'icon' => 'compass', 'head' => __( 'Próximos pasos claros.', 'match' ), 'text' => __( 'Después de la consulta, te daremos un plan detallado y un cronograma.', 'match' ) ),
		),
	);
}

/**
 * Datos de la portada: los campos PCF de la página de inicio sobre los fijos.
 * Se calcula una vez por petición porque lo leen seis template parts.
 */
function match_home(): array {
	static $home = null;

	if ( null !== $home ) {
		return $home;
	}

	$home    = match_home_data();
	$post_id = (int) get_option( 'page_on_front' );

	if ( ! $post_id || ! function_exists( 'get_field' ) || ! get_field( 'hero_lead', $post_id ) ) {
		return $home;
	}

	$f = static fn( string $name ) => get_field( $name, $post_id );

	// Escalares y listas: el valor del campo si no está vacío.
	foreach ( array( 'hero_lead', 'hero_lead_strong', 'hero_lead_2', 'hero_photo', 'hero_card_title', 'hero_card_sub', 'hero_card_cta', 'hero_logos', 't_eyebrow', 't_title', 't_title_2', 't_photo', 't_cta_strong', 't_cta_text', 't_cta_label', 't_intro', 't_intro_2', 't_note_title', 't_note_text', 't_logos', 'v_eyebrow', 'v_title', 's_eyebrow', 's_title', 's_title_2', 'm_eyebrow', 'm_title', 'm_title_2', 'c_lead', 'c_lead_muted', 'm_split', 'm_stats', 'c_bullets' ) as $name ) {
		$value = $f( $name );
		if ( ! empty( $value ) ) {
			$home[ $name ] = $value;
		}
	}

	// Grupos: sub-campo a sub-campo.
	foreach ( array( 't_review', 'm_hero', 'm_gauge' ) as $name ) {
		foreach ( (array) $f( $name ) as $sub => $value ) {
			if ( ! empty( $value ) ) {
				$home[ $name ][ $sub ] = $value;
			}
		}
	}

	$soluciones = array();
	foreach ( (array) $f( 'soluciones' ) as $row ) {
		$page_id = (int) ( $row['page'] ?? 0 );
		if ( ! $page_id ) {
			continue;
		}
		$slug         = (string) get_post_field( 'post_name', $page_id );
		$soluciones[] = array(
			'slug'    => $slug,
			'url'     => (string) get_permalink( $page_id ),
			'kicker'  => (string) ( $row['kicker'] ?? '' ),
			'title'   => (string) ( $row['title'] ?: get_the_title( $page_id ) ),
			'text'    => (string) ( $row['text'] ?? '' ),
			'photo'   => (string) ( $row['photo'] ?: get_theme_file_uri( "assets/img/solucion-{$slug}.webp" ) ),
			'overlay' => (string) ( $row['overlay'] ?: 'rgba(15,11,89,0.81)' ),
			'light'   => ! empty( $row['light'] ),
		);
	}
	if ( $soluciones ) {
		$home['soluciones'] = $soluciones;
	}

	return $home;
}
