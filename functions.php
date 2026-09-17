<?php
/**
 * Tema Match.
 */

defined( 'ABSPATH' ) || exit;

define( 'MATCH_VERSION', '0.1.0' );

/**
 * Soportes del tema.
 */
function match_setup(): void {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array( 'height' => 46, 'width' => 140, 'flex-width' => true ) );

	register_nav_menus(
		array(
			'primary'     => __( 'Navegación principal', 'match' ),
			'footer_jobs' => __( 'Footer — Job Board', 'match' ),
			'footer_nav'  => __( 'Footer — Navegación', 'match' ),
			'footer_social' => __( 'Footer — Redes', 'match' ),
		)
	);
}
add_action( 'after_setup_theme', 'match_setup' );

/**
 * Estilos y scripts.
 *
 * Orden de carga: fuentes → tokens → base → componentes → header → footer →
 * (solución) → AOS → Lenis → Tailwind. Tailwind va al final a propósito: sus
 * utilidades no están en @layer y deben ganarle al CSS BEM a igual
 * especificidad. Se compila con `npm run build` (ver README).
 *
 * Las fuentes se sirven localmente desde assets/fonts para no depender de
 * Google Fonts: evita el salto de layout y el problema de privacidad de
 * enviar la IP de cada visitante a un tercero.
 */
function match_assets(): void {
	$css = array(
		'match-fonts'      => array( 'assets/css/fonts.css', array() ),
		'match-tokens'     => array( 'assets/css/tokens.css', array( 'match-fonts' ) ),
		'match-base'       => array( 'assets/css/base.css', array( 'match-tokens' ) ),
		'match-components' => array( 'assets/css/components.css', array( 'match-base' ) ),
		'match-home'       => array( 'assets/css/home.css', array( 'match-components' ) ),
		'match-header'     => array( 'assets/css/header.css', array( 'match-home' ) ),
		'match-footer'     => array( 'assets/css/footer.css', array( 'match-components' ) ),
	);

	foreach ( $css as $handle => list( $path, $deps ) ) {
		wp_enqueue_style( $handle, get_theme_file_uri( $path ), $deps, MATCH_VERSION );
	}

	if ( is_page_template( 'template-solucion.php' ) ) {
		wp_enqueue_style( 'match-solucion', get_theme_file_uri( 'assets/css/solucion.css' ), array( 'match-footer' ), MATCH_VERSION );
	}

	if ( is_page_template( 'template-design-system.php' ) ) {
		wp_enqueue_style( 'match-styleguide', get_theme_file_uri( 'assets/css/styleguide.css' ), array( 'match-footer' ), MATCH_VERSION );
	}

	if ( match_is_jobboard_view() ) {
		wp_enqueue_style( 'match-jobboard', get_theme_file_uri( 'assets/css/jobboard.css' ), array( 'match-footer' ), MATCH_VERSION );
	}

	wp_enqueue_style( 'match-style', get_stylesheet_uri(), array( 'match-header', 'match-footer' ), MATCH_VERSION );

	// Animaciones de entrada (AOS) y utilidades (Tailwind), siempre al final.
	wp_enqueue_style( 'match-aos', get_theme_file_uri( 'assets/vendor/aos.css' ), array( 'match-style' ), '2.3.4' );
	wp_enqueue_style( 'match-lenis', get_theme_file_uri( 'assets/vendor/lenis.css' ), array( 'match-aos' ), '1.3.26' );
	wp_enqueue_style( 'match-tailwind', get_theme_file_uri( 'assets/css/tailwind.css' ), array( 'match-lenis' ), MATCH_VERSION );

	wp_enqueue_script( 'aos', get_theme_file_uri( 'assets/vendor/aos.js' ), array(), '2.3.4', true );
	wp_enqueue_script( 'embla-carousel', get_theme_file_uri( 'assets/vendor/embla-carousel.umd.js' ), array(), '8.6.0', true );
	wp_enqueue_script( 'lenis', get_theme_file_uri( 'assets/vendor/lenis.min.js' ), array(), '1.3.26', true );
	wp_enqueue_script( 'match-app', get_theme_file_uri( 'assets/js/app.js' ), array( 'aos', 'embla-carousel', 'lenis' ), MATCH_VERSION, true );
	wp_localize_script(
		'match-app',
		'MatchJB',
		array(
			'rest'     => esc_url_raw( rest_url( 'match/v1/' ) ),
			'nonce'    => wp_create_nonce( 'wp_rest' ),
			'loggedIn' => is_user_logged_in(),
			'loginUrl' => wp_login_url( home_url( add_query_arg( array(), $GLOBALS['wp']->request ?? '' ) ) ),
		)
	);

	// El plugin hereda los tokens del tema.
	if ( wp_style_is( 'mjb-front', 'registered' ) ) {
		wp_style_add_data( 'mjb-front', 'after', '' );
	}
}
add_action( 'wp_enqueue_scripts', 'match_assets' );

/**
 * Precarga de las fuentes que se ven en el primer pintado.
 */
function match_preload_fonts(): void {
	$fonts = array(
		'assets/fonts/poppins-regular.woff2',
		'assets/fonts/poppins-medium.woff2',
		'assets/fonts/poppins-semibold.woff2',
		'assets/fonts/open-sauce-sans-bold.woff2',
	);

	foreach ( $fonts as $font ) {
		if ( file_exists( get_theme_file_path( $font ) ) ) {
			printf(
				'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
				esc_url( get_theme_file_uri( $font ) )
			);
		}
	}
}
add_action( 'wp_head', 'match_preload_fonts', 1 );

/**
 * ¿Está activo el plugin del Job Board?
 *
 * Todas las plantillas de vacantes lo comprueban antes de pintar nada, para que
 * el tema no reviente si el plugin se desactiva.
 */
function match_job_board_active(): bool {
	return class_exists( 'MJB_Helpers' );
}

/**
 * Logo de empresa con respaldo de monograma.
 *
 * El CRM entrega logos tipo favicon (32–64 px) y algunos vienen vacíos, así que
 * la plantilla nunca asume que hay imagen.
 */
function match_company_logo( int $job_id, int $size = 96 ): void {
	if ( ! match_job_board_active() ) {
		return;
	}

	$logo    = (string) MJB_Helpers::get_meta( $job_id, 'company_logo' );
	$company = (string) MJB_Helpers::get_meta( $job_id, 'company_name' );

	printf( '<div class="match-logo" style="--logo-size:%dpx">', (int) $size );

	if ( $logo ) {
		printf(
			'<img src="%s" alt="" loading="lazy" decoding="async">',
			esc_url( $logo )
		);
	} else {
		printf(
			'<span class="match-logo__monogram" aria-hidden="true">%s</span>',
			esc_html( mb_substr( $company ?: '—', 0, 1 ) )
		);
	}

	echo '</div>';
}

/**
 * Título de sección reutilizable (eyebrow + titular en dos tonos).
 *
 * $dark pinta la variante blanca (sección Vacantes).
 */
function match_section_head( string $eyebrow, string $line1, string $line2 = '', string $size = 'h2', bool $dark = false ): void {
	?>
	<header class="match-section__head<?php echo $dark ? ' match-section__head--dark' : ''; ?>" data-aos="fade-up">
		<p class="match-eyebrow">
			<span class="match-eyebrow__mark" aria-hidden="true"><?php echo match_icon( $dark ? 'eyebrow-mark-light' : 'eyebrow-mark' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<?php echo esc_html( $eyebrow ); ?>
		</p>
		<h2 class="match-<?php echo esc_attr( $size ); ?>">
			<?php echo esc_html( $line1 ); ?>
			<?php if ( $line2 ) : ?>
				<span class="is-secondary"><?php echo esc_html( $line2 ); ?></span>
			<?php endif; ?>
		</h2>
	</header>
	<?php
}

/**
 * Longitud del extracto en las tarjetas.
 */
function match_excerpt_length(): int {
	return 24;
}
add_filter( 'excerpt_length', 'match_excerpt_length' );

/**
 * Chevron en los ítems del menú que despliegan submenú (Figma: Icon/Chevron).
 */
function match_nav_chevron( string $title, WP_Post $item, stdClass $args ): string {
	if ( 'primary' !== ( $args->theme_location ?? '' ) || ! in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
		return $title;
	}
	return $title . match_icon( 'chevron', 'match-nav__chevron' );
}
add_filter( 'nav_menu_item_title', 'match_nav_chevron', 10, 3 );

/**
 * Datos de contacto del pie, editables desde el Personalizador.
 */
function match_customize_register( WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_section(
		'match_contact',
		array( 'title' => __( 'Match — Contacto', 'match' ), 'priority' => 30 )
	);

	$fields = array(
		'match_phone'        => array( __( 'Teléfono', 'match' ), '(+51) 908825057' ),
		'match_email'        => array( __( 'Correo', 'match' ), 'conversemos@match.win' ),
		'match_ruc'          => array( __( 'RUC', 'match' ), '20508540788' ),
		'match_razon_social' => array( __( 'Razón social', 'match' ), 'MATCH CONSULTORES SAC' ),
	);

	foreach ( $fields as $id => list( $label, $default ) ) {
		$wp_customize->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( $id, array( 'label' => $label, 'section' => 'match_contact', 'type' => 'text' ) );
	}
}
add_action( 'customize_register', 'match_customize_register' );

/**
 * Formulario "Hablemos": valida, envía por correo y vuelve a la portada con
 * el estado en la URL.
 */
function match_handle_contact(): void {
	$back = home_url( '/' );

	if ( ! isset( $_POST['match_contact_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['match_contact_nonce'] ), 'match_contact' ) || ! empty( $_POST['match_web'] ) ) {
		wp_safe_redirect( add_query_arg( 'contacto', 'error', $back ) . '#contacto' );
		exit;
	}

	$perfil  = isset( $_POST['perfil'] ) && 'profesional' === $_POST['perfil'] ? 'profesional' : 'empresa';
	$nombre  = sanitize_text_field( wp_unslash( $_POST['nombre'] ?? '' ) );
	$correo  = sanitize_email( wp_unslash( $_POST['correo'] ?? '' ) );
	$mensaje = sanitize_textarea_field( wp_unslash( $_POST['mensaje'] ?? '' ) );

	if ( ! $nombre || ! is_email( $correo ) || ! $mensaje ) {
		wp_safe_redirect( add_query_arg( array( 'contacto' => 'error', 'perfil' => $perfil ), $back ) . '#contacto' );
		exit;
	}

	$to      = get_theme_mod( 'match_email', get_option( 'admin_email' ) );
	$subject = sprintf( '[Match] Contacto (%s): %s', $perfil, $nombre );
	$body    = "Perfil: {$perfil}\nNombre: {$nombre}\nCorreo: {$correo}\n\n{$mensaje}";
	$sent    = wp_mail( $to, $subject, $body, array( 'Reply-To: ' . $correo ) );

	wp_safe_redirect( add_query_arg( array( 'contacto' => $sent ? 'ok' : 'error', 'perfil' => $perfil ), $back ) . '#contacto' );
	exit;
}
add_action( 'admin_post_match_contact', 'match_handle_contact' );
add_action( 'admin_post_nopriv_match_contact', 'match_handle_contact' );

require_once get_theme_file_path( 'inc/template-tags.php' );
require_once get_theme_file_path( 'inc/jobs-filters.php' );
require_once get_theme_file_path( 'inc/jobboard.php' );
require_once get_theme_file_path( 'inc/soluciones-data.php' );
