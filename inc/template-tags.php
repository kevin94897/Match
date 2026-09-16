<?php
/**
 * Utilidades de plantilla.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Meta de una vacante lista para pintar: jornada y lugar.
 */
function match_job_meta( int $job_id ): array {
	if ( ! match_job_board_active() ) {
		return array();
	}

	$type    = (string) MJB_Helpers::get_meta( $job_id, 'employment_type' );
	$mode    = (string) MJB_Helpers::get_meta( $job_id, 'work_mode' );
	$city    = (string) MJB_Helpers::get_meta( $job_id, 'city' );
	$country = (string) MJB_Helpers::get_meta( $job_id, 'country' );

	$place = array_filter( array( $mode ? MJB_Helpers::work_mode_label( $mode ) : '', $city ?: $country ) );

	return array_values(
		array_filter(
			array(
				$type ? MJB_Helpers::employment_type_label( $type ) : '',
				$place ? implode( ', ', $place ) : '',
			)
		)
	);
}

/**
 * Salario formateado.
 */
function match_job_salary( int $job_id ): string {
	return match_job_board_active() ? MJB_Helpers::format_salary( $job_id ) : '';
}

/**
 * Enlace al archivo de vacantes, seguro aunque el plugin esté apagado.
 */
function match_jobs_url(): string {
	if ( ! match_job_board_active() ) {
		return home_url( '/' );
	}
	return (string) get_post_type_archive_link( MJB_Helpers::job_post_type() );
}

/**
 * SVG inline desde assets/img/icons. Los iconos usan currentColor, así que
 * heredan el color del texto que los rodea.
 */
function match_icon( string $name, string $class = '' ): string {
	$file = get_theme_file_path( "assets/img/icons/{$name}.svg" );
	if ( ! file_exists( $file ) ) {
		return '';
	}

	$attrs = 'aria-hidden="true" focusable="false"' . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' );

	return (string) preg_replace( '/<svg /', "<svg {$attrs} ", (string) file_get_contents( $file ), 1 );
}

/**
 * Wordmark de Match. El texto va en currentColor: negro en la barra y el
 * pie, blanco sobre el hero. El isotipo conserva su celeste.
 */
function match_logo( string $class = '' ): string {
	$file = get_theme_file_path( 'assets/img/logo.svg' );
	if ( ! file_exists( $file ) ) {
		return '<span class="match-wordmark">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
	}

	$attrs = 'role="img" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . '"' . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' );

	return (string) preg_replace( '/<svg /', "<svg {$attrs} ", (string) file_get_contents( $file ), 1 );
}

/**
 * Menú principal. Sin menú asignado pinta los ítems del diseño para que la
 * maqueta se vea completa desde el primer día.
 */
function match_primary_menu( string $class ): void {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => $class,
				'depth'          => 2,
				'fallback_cb'    => false,
			)
		);
		return;
	}

	// Las soluciones son las cuatro del acordeón de la portada.
	$solutions = array( 'Executive', 'Assessment', 'Outplacement', 'Personnel' );
	$items     = array(
		array( __( 'Inicio', 'match' ), home_url( '/' ), is_front_page(), array() ),
		array( __( 'Soluciones', 'match' ), home_url( '/soluciones/' ), false, $solutions ),
		array( __( 'Prensa & News', 'match' ), home_url( '/prensa/' ), false, array() ),
	);

	echo '<ul class="' . esc_attr( $class ) . '">';
	foreach ( $items as list( $label, $url, $current, $children ) ) {
		printf(
			'<li class="menu-item%s%s"><a href="%s"%s>%s%s</a>',
			$current ? ' current-menu-item' : '',
			$children ? ' menu-item-has-children' : '',
			esc_url( $url ),
			$current ? ' aria-current="page"' : '',
			esc_html( $label ),
			$children ? match_icon( 'chevron', 'match-nav__chevron' ) : '' // phpcs:ignore WordPress.Security.EscapeOutput
		);

		if ( $children ) {
			echo '<ul class="sub-menu">';
			foreach ( $children as $child ) {
				printf( '<li class="menu-item"><a href="%s">%s</a></li>', esc_url( trailingslashit( $url ) . sanitize_title( $child ) . '/' ), esc_html( $child ) );
			}
			echo '</ul>';
		}

		echo '</li>';
	}
	echo '</ul>';
}

/**
 * Columna del pie. Igual que arriba: menú asignado o enlaces del diseño.
 */
function match_footer_menu( string $location ): void {
	if ( has_nav_menu( $location ) ) {
		wp_nav_menu(
			array(
				'theme_location' => $location,
				'container'      => false,
				'menu_class'     => 'match-footer__links',
				'depth'          => 1,
			)
		);
		return;
	}

	$fallback = array(
		'footer_jobs'   => array(
			array( __( 'Subir mi CV', 'match' ), match_jobs_url() ),
			array( __( 'Iniciar sesión', 'match' ), wp_login_url() ),
			array( __( 'Destacado', 'match' ), match_jobs_url() ),
		),
		'footer_nav'    => array(
			array( __( 'Inicio', 'match' ), home_url( '/' ) ),
			array( __( 'Vacantes', 'match' ), match_jobs_url() ),
			array( __( 'Soluciones', 'match' ), home_url( '/soluciones/' ) ),
			array( __( 'Nosotros', 'match' ), home_url( '/nosotros/' ) ),
		),
		'footer_social' => array(
			array( 'LinkedIn', 'https://www.linkedin.com/' ),
			array( 'Instagram', 'https://www.instagram.com/' ),
			array( 'X (Twitter)', 'https://x.com/' ),
		),
	);

	if ( empty( $fallback[ $location ] ) ) {
		return;
	}

	echo '<ul class="match-footer__links">';
	foreach ( $fallback[ $location ] as list( $label, $url ) ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Logos de clientes del diseño (marquee del hero y grilla de testimonios).
 * Archivos en assets/img/logos/{slug}.png.
 */
function match_client_logos(): array {
	return array(
		'mmg'        => 'MMG',
		'ferreycorp' => 'Ferreycorp',
		'elektra'    => 'Elektra',
		'beat'       => 'Beat',
		'credicorp'  => 'Credicorp',
		'olx'        => 'OLX',
		'abb'        => 'ABB',
		'intercorp'  => 'Intercorp',
		'alfa-laval' => 'Alfa Laval',
		'aenza'      => 'Aenza',
	);
}

/**
 * Vacantes publicadas (para el "+N posiciones" del hero).
 */
function match_open_jobs_count(): int {
	if ( ! match_job_board_active() ) {
		return 25;
	}
	$count = (int) wp_count_posts( MJB_Helpers::job_post_type() )->publish;
	return $count > 0 ? $count : 25;
}

/**
 * Datos de una vacante listos para la fila de la portada.
 */
function match_job_data( int $job_id ): array {
	$city    = (string) MJB_Helpers::get_meta( $job_id, 'city' );
	$country = (string) MJB_Helpers::get_meta( $job_id, 'country' );
	$mode    = (string) MJB_Helpers::get_meta( $job_id, 'work_mode' );
	$type    = (string) MJB_Helpers::get_meta( $job_id, 'employment_type' );

	return array(
		'id'       => (string) $job_id,
		'company'  => (string) MJB_Helpers::get_meta( $job_id, 'company_name' ),
		'logo'     => (string) MJB_Helpers::get_meta( $job_id, 'company_logo' ),
		'title'    => get_the_title( $job_id ),
		'url'      => (string) get_permalink( $job_id ),
		'salary'   => MJB_Helpers::format_salary( $job_id ),
		'type'     => $type ? MJB_Helpers::employment_type_label( $type ) : '',
		'place'    => trim( implode( ', ', array_filter( array( $mode ? MJB_Helpers::work_mode_label( $mode ) : '', $city ?: $country ) ) ), ', ' ),
		'skills'   => (array) MJB_Helpers::get_meta( $job_id, 'skills', array() ),
		'date'     => get_the_date( 'j M, Y', $job_id ),
		'datetime' => get_the_date( 'c', $job_id ),
		'saved'    => is_user_logged_in() && MJB_Query::is_saved( $job_id ),
	);
}

/**
 * Vacantes de la portada: las últimas del Job Board o, si aún no hay
 * sincronización, las cuatro filas de ejemplo del diseño.
 */
function match_home_jobs( int $count = 4 ): array {
	$jobs = array();

	if ( match_job_board_active() ) {
		$query = MJB_Query::featured( $count );
		foreach ( $query->posts as $post ) {
			$jobs[] = match_job_data( $post->ID );
		}
	}

	if ( $jobs ) {
		return $jobs;
	}

	$logo = fn( string $slug ): string => get_theme_file_uri( "assets/img/logos/{$slug}.png" );
	$demo = array(
		array( 'novatech', 'NovaTech', 'Senior Product Manager de Growth', 'S/ 9,000–13,000 /mes', 'Tiempo completo, ejecutivo', 'Remoto, Perú', array( 'Roadmap', 'Agile', 'Stakeholders', 'Priorización' ) ),
		array( 'intercorp', 'Grupo Intercorp', 'Analista de Datos y Business Intelligence', 'S/ 6,000–8,500 /mes', 'Tiempo completo', 'Híbrido, Lima', array( 'SQL', 'Power BI', 'ETL' ) ),
		array( 'abb', 'ABB Technology', 'Community Manager de Redes Sociales', 'S/ 3,500–5,000 /mes', 'Tiempo completo', 'Presencial, Lima', array( 'Contenido', 'Meta Ads', 'Analítica' ) ),
		array( 'mmg', 'MMG Limited', 'Gerente de Finanzas Corporativas', 'S/ 10,000–14,000 /mes', 'Tiempo completo, ejecutivo', 'Presencial, Lima', array( 'Finanzas', 'Tesorería', 'Minería' ) ),
	);

	foreach ( $demo as $i => list( $slug, $company, $title, $salary, $type, $place, $skills ) ) {
		$jobs[] = array(
			'id'       => 'demo-' . $i,
			'company'  => $company,
			'logo'     => $logo( $slug ),
			'title'    => $title,
			'url'      => match_jobs_url(),
			'salary'   => $salary,
			'type'     => $type,
			'place'    => $place,
			'skills'   => $skills,
			'date'     => '8 abr, 2026',
			'datetime' => '2026-04-08',
			'saved'    => false,
		);
	}

	return $jobs;
}
