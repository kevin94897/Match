<?php
/**
 * Job Board: login y panel (Figma: Login 4308:3581, Inicio público 4529:3).
 *
 * Páginas (se crean desde el admin con estas plantillas):
 * - "Job Board"       → template-jobboard.php  (panel con barra lateral)
 * - "Iniciar sesión"  → template-login.php     (hija de Job Board)
 *
 * El login usa el flujo nativo: el formulario envía a wp-login.php y aquí se
 * redirige de vuelta con el error o al panel al entrar. wp_login_url() pasa
 * a devolver la página de login del tema, así el navbar y el plugin
 * apuntan al diseño en vez de a wp-login.php.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Página que usa una plantilla, o null.
 */
function match_page_with_template( string $template ): ?WP_Post {
	static $cache = array();

	if ( ! array_key_exists( $template, $cache ) ) {
		$pages              = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => $template, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			)
		);
		$cache[ $template ] = $pages ? $pages[0] : null;
	}

	return $cache[ $template ];
}

function match_jobboard_url(): string {
	$page = match_page_with_template( 'template-jobboard.php' );
	return $page ? (string) get_permalink( $page ) : match_jobs_url();
}

function match_login_page_url(): string {
	$page = match_page_with_template( 'template-login.php' );
	return $page ? (string) get_permalink( $page ) : '';
}

/**
 * Registro en dos pasos (Figma: Registro 4341:3554, Subir CV 4462:3).
 */
function match_registro_page_url(): string {
	return match_jobboard_page_url( 'template-registro.php' );
}

function match_registro_cv_page_url(): string {
	return match_jobboard_page_url( 'template-registro-cv.php' );
}

/**
 * wp_login_url() → página de login del tema (con redirect_to).
 */
function match_filter_login_url( string $login_url, string $redirect ): string {
	$page = match_login_page_url();

	if ( ! $page ) {
		return $login_url;
	}

	return $redirect ? add_query_arg( 'redirect_to', rawurlencode( $redirect ), $page ) : $page;
}
add_filter( 'login_url', 'match_filter_login_url', 10, 2 );

/**
 * wp-login.php sin acción (GET) → página de login del tema. Las acciones
 * (lostpassword, register, logout, postpass…) siguen en wp-login.php.
 */
function match_redirect_wp_login(): void {
	$page = match_login_page_url();
	if ( ! $page || 'GET' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
		return;
	}
	$action = isset( $_GET['action'] ) ? sanitize_key( $_GET['action'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( '' !== $action && 'login' !== $action ) {
		return;
	}
	$args = array();
	foreach ( array( 'redirect_to', 'loggedout', 'login' ) as $key ) {
		if ( ! empty( $_GET[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$args[ $key ] = rawurlencode( sanitize_text_field( wp_unslash( $_GET[ $key ] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
	}
	wp_safe_redirect( add_query_arg( $args, $page ) );
	exit;
}
add_action( 'login_init', 'match_redirect_wp_login' );

/**
 * Credenciales incorrectas → de vuelta al login del tema con el aviso.
 */
function match_login_failed(): void {
	$page = match_login_page_url();
	if ( ! $page ) {
		return;
	}
	$redirect = isset( $_POST['redirect_to'] ) ? sanitize_text_field( wp_unslash( $_POST['redirect_to'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	wp_safe_redirect( add_query_arg( array_filter( array( 'login' => 'failed', 'redirect_to' => $redirect ? rawurlencode( $redirect ) : '' ) ), $page ) );
	exit;
}
add_action( 'wp_login_failed', 'match_login_failed' );

/**
 * Tras entrar, al panel (salvo redirect_to explícito). Los administradores
 * siguen yendo al escritorio.
 */
function match_login_redirect( string $redirect_to, string $requested, $user ): string {
	if ( $user instanceof WP_User && user_can( $user, 'manage_options' ) ) {
		return $redirect_to;
	}
	return $requested && admin_url() !== $requested ? $redirect_to : match_jobboard_url();
}
add_filter( 'login_redirect', 'match_login_redirect', 10, 3 );

/**
 * Usuario ya identificado en el login → al panel.
 */
function match_login_page_guard(): void {
	if ( ! is_page_template( 'template-login.php' ) ) {
		return;
	}
	if ( is_user_logged_in() ) {
		wp_safe_redirect( match_jobboard_url() );
		exit;
	}
	// wp-login.php rechaza el envío si no existe la cookie de prueba, que
	// normalmente pone él mismo al mostrar su formulario.
	if ( ! headers_sent() ) {
		setcookie( TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
		if ( SITECOOKIEPATH !== COOKIEPATH ) {
			setcookie( TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
		}
	}
}
add_action( 'template_redirect', 'match_login_page_guard' );

/**
 * Aviso del login según la URL (?login=failed, ?loggedout=true, ?registered=1).
 *
 * @return array{type:string,text:string}|null
 */
function match_login_notice(): ?array {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['login'] ) && 'failed' === $_GET['login'] ) {
		return array( 'type' => 'error', 'text' => __( 'Correo o contraseña incorrectos. Inténtalo de nuevo.', 'match' ) );
	}
	if ( ! empty( $_GET['loggedout'] ) ) {
		return array( 'type' => 'ok', 'text' => __( 'Cerraste sesión correctamente.', 'match' ) );
	}
	if ( ! empty( $_GET['registered'] ) ) {
		return array( 'type' => 'ok', 'text' => __( 'Cuenta creada. Ya puedes iniciar sesión.', 'match' ) );
	}
	// phpcs:enable
	return null;
}

/**
 * Aviso del paso 1 de registro según ?registro=error&msg=.
 *
 * @return array{type:string,text:string}|null
 */
function match_registro_notice(): ?array {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	if ( 'error' !== ( $_GET['registro'] ?? '' ) ) {
		return null;
	}
	$msg = isset( $_GET['msg'] ) ? sanitize_text_field( wp_unslash( $_GET['msg'] ) ) : '';
	// phpcs:enable
	return array( 'type' => 'error', 'text' => $msg ?: __( 'No pudimos crear tu cuenta. Inténtalo de nuevo.', 'match' ) );
}

/**
 * Aviso del paso 2 de registro (subir CV) según ?paso2=error&msg=.
 *
 * @return array{type:string,text:string}|null
 */
function match_registro_cv_notice(): ?array {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	if ( 'error' !== ( $_GET['paso2'] ?? '' ) ) {
		return null;
	}
	$msg = isset( $_GET['msg'] ) ? sanitize_text_field( wp_unslash( $_GET['msg'] ) ) : '';
	// phpcs:enable
	return array( 'type' => 'error', 'text' => $msg ?: __( 'No pudimos subir tu CV. Inténtalo de nuevo.', 'match' ) );
}

/**
 * Paso 1: crea la cuenta solo con el correo (sin pedir contraseña, como en
 * Figma) y loguea automáticamente para que el paso 2 (subir CV) no pida
 * iniciar sesión de nuevo. La contraseña es aleatoria y nunca se expone: el
 * correo nativo de WordPress (wp_new_user_notification) manda el enlace
 * para fijarla, igual que "Enviar link de reseteo" en Perfil.
 */
function match_handle_register(): void {
	$back = match_registro_page_url() ?: home_url( '/' );

	if ( ! isset( $_POST['_match_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_match_nonce'] ), 'match_register' ) || ! empty( $_POST['match_web'] ) ) {
		wp_safe_redirect( add_query_arg( 'registro', 'error', $back ) );
		exit;
	}

	$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	if ( ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( array( 'registro' => 'error', 'msg' => rawurlencode( __( 'Escribe un correo válido.', 'match' ) ) ), $back ) );
		exit;
	}
	if ( email_exists( $email ) ) {
		wp_safe_redirect( add_query_arg( array( 'registro' => 'error', 'msg' => rawurlencode( __( 'Ese correo ya tiene una cuenta. Inicia sesión.', 'match' ) ) ), $back ) );
		exit;
	}

	$username = sanitize_user( current( explode( '@', $email ) ), true ) ?: 'usuario';
	$base     = $username;
	$i        = 1;
	while ( username_exists( $username ) ) {
		$username = $base . ( ++$i );
	}

	$user_id = wp_insert_user(
		array(
			'user_login' => $username,
			'user_email' => $email,
			'user_pass'  => wp_generate_password( 24, true, true ),
			'role'       => 'subscriber',
		)
	);

	if ( is_wp_error( $user_id ) ) {
		wp_safe_redirect( add_query_arg( array( 'registro' => 'error', 'msg' => rawurlencode( $user_id->get_error_message() ) ), $back ) );
		exit;
	}

	wp_new_user_notification( $user_id, null, 'user' );

	wp_set_current_user( $user_id );
	wp_set_auth_cookie( $user_id, true );

	wp_safe_redirect( match_registro_cv_page_url() ?: match_jobboard_url() );
	exit;
}
add_action( 'admin_post_match_register', 'match_handle_register' );
add_action( 'admin_post_nopriv_match_register', 'match_handle_register' );

/**
 * Paso 2: sube el CV con el mismo mecanismo que el perfil (MJB_CV::store(),
 * mismos metas mjb_cv_*, ver match_profile_cv()), pero termina en el panel
 * en vez de en Perfil. No hace falta borrar un CV anterior: la cuenta recién
 * se creó en el paso 1.
 */
function match_handle_register_cv(): void {
	$back = match_registro_cv_page_url() ?: match_jobboard_url();

	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( wp_login_url( $back ) );
		exit;
	}
	if ( ! isset( $_POST['_match_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_match_nonce'] ), 'match_register_cv' ) ) {
		wp_safe_redirect( add_query_arg( 'paso2', 'error', $back ) );
		exit;
	}
	if ( ! match_job_board_active() ) {
		wp_safe_redirect( add_query_arg( array( 'paso2' => 'error', 'msg' => rawurlencode( __( 'El Job Board no está activo.', 'match' ) ) ), $back ) );
		exit;
	}
	if ( empty( $_FILES['cv']['name'] ) ) {
		wp_safe_redirect( add_query_arg( array( 'paso2' => 'error', 'msg' => rawurlencode( __( 'Elige un archivo.', 'match' ) ) ), $back ) );
		exit;
	}

	$stored = MJB_CV::store( $_FILES['cv'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	if ( is_wp_error( $stored ) ) {
		wp_safe_redirect( add_query_arg( array( 'paso2' => 'error', 'msg' => rawurlencode( $stored->get_error_message() ) ), $back ) );
		exit;
	}

	$user = wp_get_current_user();
	update_user_meta( $user->ID, 'mjb_cv_file', $stored );
	update_user_meta( $user->ID, 'mjb_cv_name', sanitize_file_name( wp_unslash( $_FILES['cv']['name'] ) ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	update_user_meta( $user->ID, 'mjb_cv_date', current_time( 'mysql' ) );

	wp_safe_redirect( match_jobboard_url() );
	exit;
}
add_action( 'admin_post_match_register_cv', 'match_handle_register_cv' );

/**
 * URL de "Continuar con Google". Vacía hasta que se conecte un proveedor
 * OAuth (filtro match/google_login_url).
 */
function match_google_login_url(): string {
	return (string) apply_filters( 'match/google_login_url', '' );
}

/**
 * Vacantes destacadas del panel: las últimas.
 */
function match_jobboard_featured( int $count = 3 ): array {
	return match_jobs_list( array(), $count )['jobs'];
}

/**
 * Áreas del panel: vacantes agrupadas por industria (facetas), hasta $areas
 * industrias con $per vacantes cada una.
 *
 * @return array<int, array{name:string,slug:string,jobs:array}>
 */
function match_jobboard_areas( int $areas = 3, int $per = 3 ): array {
	$facets = match_jobs_facets( array() )['industria'] ?? array();
	$out    = array();

	foreach ( array_slice( $facets, 0, $areas ) as $facet ) {
		$jobs = match_jobs_list( array( 'industria' => $facet['slug'] ), $per )['jobs'];
		if ( $jobs ) {
			$out[] = array( 'name' => $facet['name'], 'slug' => $facet['slug'], 'jobs' => $jobs );
		}
	}

	return $out;
}

/**
 * Oculta la barra de administración de WordPress en el frontend.
 *
 * TEMPORAL: por ahora se oculta para todos los usuarios. Cuando se quiera
 * restaurar para editores, volver a: current_user_can( 'edit_posts' ) ? $show : false
 */
function match_hide_admin_bar( bool $show ): bool {
	return false;
}
add_filter( 'show_admin_bar', 'match_hide_admin_bar' );

/**
 * Onboarding del panel (Figma: Job-Board/Onboarding — Bienvenida, 4409:3).
 * Se muestra una vez a cada usuario; al cerrarlo, app.js llama a
 * match_onboarding_done y queda en user meta. ?onboarding=1 lo fuerza
 * para revisarlo.
 */
function match_jobboard_show_onboarding(): bool {
	if ( ! is_user_logged_in() ) {
		return false;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ! empty( $_GET['onboarding'] ) ) {
		return true;
	}
	return ! get_user_meta( get_current_user_id(), 'match_onboarding_done', true );
}

function match_onboarding_done(): void {
	check_ajax_referer( 'match_onboarding', 'nonce' );
	update_user_meta( get_current_user_id(), 'match_onboarding_done', current_time( 'mysql' ) );
	wp_send_json_success();
}
add_action( 'wp_ajax_match_onboarding_done', 'match_onboarding_done' );

/**
 * URL del perfil: la página con la plantilla "Job Board — Perfil" o, si no
 * existe, el perfil nativo de WordPress (filtro match/profile_url).
 */
function match_profile_url(): string {
	return (string) apply_filters( 'match/profile_url', match_jobboard_page_url( 'template-profile.php' ) ?: get_edit_profile_url() );
}

/**
 * URL de "Cerrar sesión": vuelve al login del tema (con el aviso de
 * ?loggedout=true) o, si no existe esa página, al inicio del sitio.
 * Única fuente de esta URL: la usan la barra lateral y el menú de cuenta
 * de la topbar.
 */
function match_logout_url(): string {
	$login = match_login_page_url();
	return wp_logout_url( $login ? add_query_arg( 'loggedout', 'true', $login ) : home_url( '/' ) );
}

/**
 * Clases de <body> para las plantillas del Job Board.
 */
function match_jobboard_body_class( array $classes ): array {
	if ( match_is_jobboard_view() ) {
		$classes[] = 'match-jobboard';
	}
	if ( is_page_template( 'template-login.php' ) ) {
		$classes[] = 'match-login';
	}
	return $classes;
}
add_filter( 'body_class', 'match_jobboard_body_class' );

/* ======================================================================
 * Sesión iniciada: perfil, CV, guardados y procesos
 * (Figma: Inicio 4125:25, Perfil 4216:3)
 * ====================================================================== */

/**
 * URL de una página del Job Board por plantilla, o '' si no existe.
 */
function match_jobboard_page_url( string $template ): string {
	$page = match_page_with_template( $template );
	return $page ? (string) get_permalink( $page ) : '';
}

/**
 * El perfil exige sesión: sin ella, al login con vuelta.
 */
function match_profile_guard(): void {
	if ( is_page_template( 'template-profile.php' ) && ! is_user_logged_in() ) {
		wp_safe_redirect( wp_login_url( (string) get_permalink() ) );
		exit;
	}
}
add_action( 'template_redirect', 'match_profile_guard' );

/**
 * Nombre corto para el saludo.
 */
function match_user_first_name( ?WP_User $user = null ): string {
	$user = $user ?: wp_get_current_user();
	return $user->first_name ?: ( explode( ' ', trim( $user->display_name ) )[0] ?: $user->user_login );
}

/**
 * CV guardado en el perfil (lo almacena el plugin en su carpeta protegida).
 *
 * @return array{file:string,name:string,ext:string,date:string,size:string,url:string}|null
 */
function match_user_cv( int $user_id = 0 ): ?array {
	$user_id = $user_id ?: get_current_user_id();

	if ( ! $user_id || ! match_job_board_active() ) {
		return null;
	}

	$file = (string) get_user_meta( $user_id, 'mjb_cv_file', true );

	if ( '' === $file || ! MJB_CV::exists( $file ) ) {
		return null;
	}

	$name = (string) get_user_meta( $user_id, 'mjb_cv_name', true ) ?: $file;
	$date = (string) get_user_meta( $user_id, 'mjb_cv_date', true );

	return array(
		'file' => $file,
		'name' => $name,
		'ext'  => strtoupper( pathinfo( $name, PATHINFO_EXTENSION ) ?: pathinfo( $file, PATHINFO_EXTENSION ) ),
		'date' => $date ? date_i18n( 'j M, Y', strtotime( $date ) ) : '',
		'size' => size_format( (int) filesize( MJB_CV::path( $file ) ), 1 ),
		'url'  => MJB_CV::signed_url( $file, 600 ),
	);
}

/**
 * Vuelve al perfil con el estado en la URL.
 */
function match_profile_redirect( string $status, string $message = '' ): void {
	wp_safe_redirect( add_query_arg( array_filter( array( 'perfil' => $status, 'msg' => $message ? rawurlencode( $message ) : '' ) ), match_profile_url() ) );
	exit;
}

/**
 * Aviso del perfil según ?perfil=.
 *
 * @return array{type:string,text:string}|null
 */
function match_profile_notice(): ?array {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	$status = isset( $_GET['perfil'] ) ? sanitize_key( $_GET['perfil'] ) : '';
	$custom = isset( $_GET['msg'] ) ? sanitize_text_field( wp_unslash( $_GET['msg'] ) ) : '';
	// phpcs:enable
	$texts = array(
		'saved' => __( 'Datos guardados.', 'match' ),
		'cv'    => __( 'CV actualizado.', 'match' ),
		'reset' => __( 'Te enviamos el enlace para crear una nueva contraseña.', 'match' ),
		'error' => $custom ?: __( 'No pudimos guardar los cambios. Inténtalo de nuevo.', 'match' ),
	);

	if ( ! isset( $texts[ $status ] ) ) {
		return null;
	}

	return array( 'type' => 'error' === $status ? 'error' : 'ok', 'text' => $texts[ $status ] );
}

/**
 * Comprobación común de los formularios del perfil.
 */
function match_profile_check( string $action ): WP_User {
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( wp_login_url( match_profile_url() ) );
		exit;
	}
	if ( ! isset( $_POST['_match_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_match_nonce'] ), $action ) ) {
		match_profile_redirect( 'error', __( 'La sesión expiró. Vuelve a intentarlo.', 'match' ) );
	}
	return wp_get_current_user();
}

/**
 * Información de cuenta: correo, nombre y apellido.
 */
function match_profile_save(): void {
	$user  = match_profile_check( 'match_profile_save' );
	$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$first = sanitize_text_field( wp_unslash( $_POST['first_name'] ?? '' ) );
	$last  = sanitize_text_field( wp_unslash( $_POST['last_name'] ?? '' ) );

	if ( ! is_email( $email ) ) {
		match_profile_redirect( 'error', __( 'Escribe un correo válido.', 'match' ) );
	}
	$owner = email_exists( $email );
	if ( $owner && (int) $owner !== $user->ID ) {
		match_profile_redirect( 'error', __( 'Ese correo ya está en uso.', 'match' ) );
	}

	$result = wp_update_user(
		array(
			'ID'           => $user->ID,
			'user_email'   => $email,
			'first_name'   => $first,
			'last_name'    => $last,
			'display_name' => trim( $first . ' ' . $last ) ?: $user->display_name,
		)
	);

	match_profile_redirect( is_wp_error( $result ) ? 'error' : 'saved' );
}
add_action( 'admin_post_match_profile_save', 'match_profile_save' );

/**
 * Subir o reemplazar el CV. Se guarda con el plugin (carpeta protegida,
 * nombre opaco) y en los mismos metas que usa el formulario de postulación.
 */
function match_profile_cv(): void {
	$user = match_profile_check( 'match_profile_cv' );

	if ( ! match_job_board_active() ) {
		match_profile_redirect( 'error', __( 'El Job Board no está activo.', 'match' ) );
	}
	if ( empty( $_FILES['cv']['name'] ) ) {
		match_profile_redirect( 'error', __( 'Elige un archivo.', 'match' ) );
	}

	$stored = MJB_CV::store( $_FILES['cv'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	if ( is_wp_error( $stored ) ) {
		match_profile_redirect( 'error', $stored->get_error_message() );
	}

	$previous = (string) get_user_meta( $user->ID, 'mjb_cv_file', true );
	if ( $previous && $previous !== $stored ) {
		MJB_CV::delete( $previous );
	}

	update_user_meta( $user->ID, 'mjb_cv_file', $stored );
	update_user_meta( $user->ID, 'mjb_cv_name', sanitize_file_name( wp_unslash( $_FILES['cv']['name'] ) ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	update_user_meta( $user->ID, 'mjb_cv_date', current_time( 'mysql' ) );

	match_profile_redirect( 'cv' );
}
add_action( 'admin_post_match_profile_cv', 'match_profile_cv' );

/**
 * Enviar el enlace de cambio de contraseña al correo de la cuenta.
 */
function match_profile_reset(): void {
	$user   = match_profile_check( 'match_profile_reset' );
	$result = retrieve_password( $user->user_login );

	match_profile_redirect( is_wp_error( $result ) ? 'error' : 'reset', is_wp_error( $result ) ? __( 'No pudimos enviar el correo. Inténtalo más tarde.', 'match' ) : '' );
}
add_action( 'admin_post_match_profile_reset', 'match_profile_reset' );

/**
 * Meta de usuario como arreglo. get_user_meta devuelve '' cuando no existe
 * y (array) '' da [''], que se colaría como un id 0.
 */
function match_user_meta_array( int $user_id, string $key ): array {
	$value = get_user_meta( $user_id, $key, true );
	return is_array( $value ) ? $value : array();
}

/**
 * Guardados del usuario: id → marca de tiempo. Los reales viven en el meta
 * del plugin (mjb_saved_jobs, solo ids) y las fechas en match_saved_dates;
 * los de ejemplo ("demo-N") en match_saved_demo para poder revisar la
 * pantalla sin CRM.
 *
 * @return array<string,int>
 */
function match_saved_map( int $user_id = 0 ): array {
	$user_id = $user_id ?: get_current_user_id();
	if ( ! $user_id ) {
		return array();
	}

	$dates = match_user_meta_array( $user_id, 'match_saved_dates' );
	$map   = array();

	if ( match_job_board_active() ) {
		foreach ( MJB_Query::saved_jobs( $user_id ) as $id ) {
			if ( $id > 0 ) {
				$map[ (string) $id ] = (int) ( $dates[ $id ] ?? 0 );
			}
		}
	}
	foreach ( match_user_meta_array( $user_id, 'match_saved_demo' ) as $key => $ts ) {
		if ( 0 === strpos( (string) $key, 'demo-' ) ) {
			$map[ (string) $key ] = (int) $ts;
		}
	}

	return $map;
}

/**
 * Alterna un guardado. Devuelve el estado final.
 */
function match_toggle_saved( string $id, int $user_id = 0 ): bool {
	$user_id = $user_id ?: get_current_user_id();

	if ( 0 === strpos( $id, 'demo-' ) ) {
		$demo = match_user_meta_array( $user_id, 'match_saved_demo' );
		if ( isset( $demo[ $id ] ) ) {
			unset( $demo[ $id ] );
			$saved = false;
		} else {
			$demo[ $id ] = time();
			$saved       = true;
		}
		update_user_meta( $user_id, 'match_saved_demo', $demo );
		return $saved;
	}

	$job_id = (int) $id;
	$ids    = match_job_board_active() ? MJB_Query::saved_jobs( $user_id ) : array();
	$dates  = match_user_meta_array( $user_id, 'match_saved_dates' );

	if ( in_array( $job_id, $ids, true ) ) {
		$ids = array_values( array_diff( $ids, array( $job_id ) ) );
		unset( $dates[ $job_id ] );
		$saved = false;
	} else {
		$ids[]            = $job_id;
		$dates[ $job_id ] = time();
		$saved            = true;
	}

	update_user_meta( $user_id, 'mjb_saved_jobs', $ids );
	update_user_meta( $user_id, 'match_saved_dates', $dates );

	return $saved;
}

/**
 * POST /wp-json/match/v1/saved/{id} alterna el guardado (sesión + nonce REST).
 */
function match_saved_rest_routes(): void {
	register_rest_route(
		'match/v1',
		'/saved/(?P<id>[a-z0-9-]+)',
		array(
			'methods'             => WP_REST_Server::CREATABLE,
			'permission_callback' => 'is_user_logged_in',
			'callback'            => function ( WP_REST_Request $request ): WP_REST_Response {
				$id    = sanitize_key( $request['id'] );
				$saved = match_toggle_saved( $id );
				return new WP_REST_Response( array( 'id' => $id, 'saved' => $saved, 'count' => count( match_saved_map() ) ) );
			},
		)
	);
}
add_action( 'rest_api_init', 'match_saved_rest_routes' );

/**
 * Vacantes guardadas del usuario, listas para la tarjeta y ordenadas por
 * fecha de guardado (más reciente primero). Cada una lleva saved_at.
 */
function match_jobboard_saved_jobs( int $limit = 0 ): array {
	if ( ! is_user_logged_in() ) {
		return array();
	}

	$map = match_saved_map();
	arsort( $map );
	$jobs = array();

	foreach ( $map as $id => $ts ) {
		if ( 0 === strpos( (string) $id, 'demo-' ) ) {
			if ( ! match_jobs_demo_mode() ) {
				continue;
			}
			$job = match_demo_jobs()[ (int) substr( $id, 5 ) ] ?? null;
		} else {
			$job = (int) $id > 0 && match_job_board_active() && 'publish' === get_post_status( (int) $id ) ? match_job_data( (int) $id ) : null;
		}
		if ( ! $job ) {
			continue;
		}
		$job['saved']    = true;
		$job['saved_at'] = $ts ? date_i18n( 'j M, Y', $ts ) : '';
		$jobs[]          = $job;
		if ( $limit && count( $jobs ) >= $limit ) {
			break;
		}
	}

	return $jobs;
}

/**
 * IDs de vacantes a las que el usuario ya postuló.
 */
function match_jobboard_applied_ids(): array {
	if ( ! is_user_logged_in() || ! match_job_board_active() ) {
		return array();
	}

	return array_values( array_filter( array_map( fn( WP_Post $app ): int => (int) MJB_Helpers::get_meta( $app->ID, 'job_id' ), MJB_Query::my_applications() ) ) );
}

/**
 * Claves de vacantes postuladas como string ("12" o "demo-0"), para
 * compararlas con las tarjetas de cualquier origen.
 */
function match_jobboard_applied_keys(): array {
	if ( match_jobs_demo_mode() ) {
		return array_map( fn( array $row ): string => (string) $row['job']['id'], match_user_applications() );
	}
	return array_map( 'strval', match_jobboard_applied_ids() );
}

/* ======================================================================
 * Mis procesos (Figma: 4105:97; vacío 4406:3)
 * ====================================================================== */

/**
 * Etapas de una postulación: meta `stage` del plugin → etiqueta, variante
 * visual de la pastilla y el texto de "Próximo paso" (columna de estado en
 * el detalle de vacante, Figma 4158:4284). El plugin hoy solo escribe
 * "sent"; las demás llegarán cuando el CRM exponga el avance (ver
 * CONTEXTO-proyecto-match.md).
 *
 * @return array<string, array{label:string,variant:string,hint:string}>
 */
function match_application_stages(): array {
	return array(
		'sent'      => array(
			'label' => __( 'Enviado', 'match' ),
			'variant' => 'sent',
			'hint' => __( 'Tu postulación fue enviada. El equipo de Match la está revisando.', 'match' ),
		),
		'review'    => array(
			'label' => __( 'En revisión', 'match' ),
			'variant' => 'review',
			'hint' => __( 'El equipo de Match está evaluando tu perfil para este proceso.', 'match' ),
		),
		'interview' => array(
			'label' => __( 'Entrevista agendada', 'match' ),
			'variant' => 'ok',
			'hint' => __( 'Match coordinará la entrevista contigo. Revisa tu correo.', 'match' ),
		),
		'final'     => array(
			'label' => __( 'Decisión final', 'match' ),
			'variant' => 'final',
			'hint' => __( 'El cliente está decidiendo. Match te contactará en cuanto haya una respuesta.', 'match' ),
		),
		'closed'    => array(
			'label' => __( 'Finalizado', 'match' ),
			'variant' => 'muted',
			'hint' => __( 'Este proceso ha finalizado.', 'match' ),
		),
	);
}

/**
 * Postulación del usuario actual a una vacante puntual (real o "demo-N"), o
 * null si no ha postulado: { stage, applied (fecha legible), applied_ts }.
 * Reutiliza match_user_applications() para no duplicar la lógica real/demo;
 * en modo demo esas cinco filas son fijas, así que sirven para revisar las
 * cinco etapas sin CRM.
 *
 * @return array{stage:string,applied:string,applied_ts:int}|null
 */
function match_job_application_data( string $job_key ): ?array {
	if ( ! is_user_logged_in() ) {
		return null;
	}

	foreach ( match_user_applications() as $row ) {
		if ( (string) $row['job']['id'] === $job_key ) {
			return array(
				'stage'      => $row['stage'],
				'applied'    => $row['applied'],
				'applied_ts' => $row['applied_ts'],
			);
		}
	}

	return null;
}

/**
 * Postulaciones del usuario listas para la fila: job (ver match_job_data),
 * stage, applied (fecha legible) y applied_ts para ordenar.
 *
 * En modo demo (sin vacantes sincronizadas) devuelve cinco filas de ejemplo
 * con todas las etapas, salvo que se pida ?demo=0.
 */
function match_user_applications( string $order = 'reciente' ): array {
	$rows = array();

	if ( match_job_board_active() && ! match_jobs_demo_mode() ) {
		foreach ( MJB_Query::my_applications() as $application ) {
			$job_id = (int) MJB_Helpers::get_meta( $application->ID, 'job_id' );
			$job    = $job_id && 'publish' === get_post_status( $job_id ) ? match_job_data( $job_id ) : array(
				'id'      => (string) $job_id,
				'company' => (string) MJB_Helpers::get_meta( $job_id, 'company_name' ),
				'logo'    => '',
				'title'   => get_the_title( $application ),
				'url'     => '',
				'type'    => '',
				'place'   => '',
			);
			$stage  = (string) MJB_Helpers::get_meta( $application->ID, 'stage', 'sent' );
			$when   = strtotime( (string) MJB_Helpers::get_meta( $application->ID, 'applied_at' ) ) ?: (int) get_post_time( 'U', true, $application );
			$rows[] = array(
				'job'        => $job,
				'stage'      => isset( match_application_stages()[ $stage ] ) ? $stage : 'sent',
				'applied'    => date_i18n( 'j M, Y', $when ),
				'applied_ts' => $when,
			);
		}
	} elseif ( match_jobs_demo_mode() && '0' !== ( $_GET['demo'] ?? '' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$stages = array( 'review', 'interview', 'closed', 'sent', 'final' );
		foreach ( array_slice( match_demo_jobs(), 0, 5 ) as $i => $job ) {
			$rows[] = array(
				'job'        => $job,
				'stage'      => $stages[ $i ],
				'applied'    => date_i18n( 'j M, Y', strtotime( '2026-04-08' ) - $i * DAY_IN_SECONDS ),
				'applied_ts' => strtotime( '2026-04-08' ) - $i * DAY_IN_SECONDS,
			);
		}
	}

	usort( $rows, fn( array $a, array $b ): int => 'antiguo' === $order ? $a['applied_ts'] <=> $b['applied_ts'] : $b['applied_ts'] <=> $a['applied_ts'] );

	return $rows;
}

/**
 * Plantillas del Job Board (comparten header, CSS y clase de <body>).
 */
function match_jobboard_templates(): array {
	return array( 'template-jobboard.php', 'template-login.php', 'template-registro.php', 'template-registro-cv.php', 'template-profile.php', 'template-procesos.php', 'template-guardados.php' );
}

/**
 * Mis procesos exige sesión.
 */
function match_procesos_guard(): void {
	if ( is_page_template( array( 'template-procesos.php', 'template-guardados.php' ) ) && ! is_user_logged_in() ) {
		wp_safe_redirect( wp_login_url( (string) get_permalink() ) );
		exit;
	}
}
add_action( 'template_redirect', 'match_procesos_guard' );

/* ======================================================================
 * Detalle de vacante (Figma: Job-Board/Detalle de vacante, 4047:3)
 * ====================================================================== */

/**
 * ¿Estamos en una vista del Job Board? Plantillas propias o el detalle de
 * una vacante real (single del CPT del plugin).
 */
function match_is_jobboard_view(): bool {
	if ( is_page_template( match_jobboard_templates() ) ) {
		return true;
	}
	return match_job_board_active() && ( is_singular( MJB_Helpers::job_post_type() ) || is_post_type_archive( MJB_Helpers::job_post_type() ) );
}

/**
 * Datos completos de una vacante para el detalle. $id es el ID del post o
 * "demo-N" para las filas de ejemplo. Null si no existe.
 *
 * Además de match_job_data(): description_html, benefits_html, requirements
 * (lista; el CRM no lo entrega aparte, así que en vacantes reales va vacío),
 * summary (filas del "Resumen del rol"), chips, closed e is_demo.
 */
function match_job_detail_data( string $id ): ?array {
	if ( 0 === strpos( $id, 'demo-' ) ) {
		$index = (int) substr( $id, 5 );
		$demo  = match_demo_jobs();
		if ( ! isset( $demo[ $index ] ) ) {
			return null;
		}
		$job = $demo[ $index ];
		$f   = $job['filters'];

		$job['description_html'] = wpautop( implode( "\n\n", array(
			sprintf( __( 'Buscamos un %1$s para formar parte del equipo de %2$s. Serás responsable de transformar grandes volúmenes de información en decisiones accionables que guíen el negocio, desde el diseño de la estrategia hasta la presentación de resultados a la alta dirección.', 'match' ), $job['title'], $job['company'] ),
			__( 'Trabajarás en estrecha colaboración con distintas áreas de la organización, diseñando tableros, modelos robustos y reportes ejecutivos que soporten la estrategia comercial. Tu trabajo tendrá impacto directo en decisiones de millones de soles.', 'match' ),
			__( 'El equipo es dinámico, orientado a resultados y con una cultura de mejora continua. Buscamos a alguien que no solo analice, sino que convierta la complejidad en historias claras que muevan a la acción.', 'match' ),
		) ) );
		$job['benefits_html'] = '<ul>' . implode( '', array_map( fn( string $s ): string => '<li>' . esc_html( $s ) . '</li>', array(
			__( 'Trabajo flexible con horario adaptable: organizas tu día según tus picos de productividad.', 'match' ),
			__( 'Seguro médico privado desde el primer mes, con cobertura para ti y un familiar directo.', 'match' ),
			__( 'Acceso a plataformas de aprendizaje y presupuesto para certificaciones.', 'match' ),
			__( '5 días de vacaciones adicionales a los legales para que puedas desconectarte de verdad.', 'match' ),
			__( 'Ambiente inclusivo y diverso, con políticas activas de equidad.', 'match' ),
		) ) ) . '</ul>';
		$job['requirements'] = array(
			__( 'Experiencia mínima de 2 años en roles similares.', 'match' ),
			__( 'Dominio avanzado de las herramientas propias del rol.', 'match' ),
			__( 'Experiencia liderando proyectos con impacto en el negocio.', 'match' ),
			__( 'Manejo de Excel avanzado (tablas dinámicas, macros, Power Query).', 'match' ),
			__( 'Capacidad analítica y orientación al detalle.', 'match' ),
			__( 'Comunicación efectiva para presentar hallazgos a stakeholders no técnicos.', 'match' ),
		);
		$job['skills']  = array_merge( $job['skills'], array( 'Excel avanzado', 'Dashboards', 'Comunicación' ) );
		$job['summary'] = array(
			__( 'Nivel', 'match' )     => $f['nivel'],
			__( 'Modalidad', 'match' ) => $f['modalidad'],
			__( 'Jornada', 'match' )   => __( 'Tiempo completo', 'match' ),
			__( 'Ubicación', 'match' ) => $f['ubicacion'] . ', Perú',
			__( 'Salario', 'match' )   => $job['salary'],
			__( 'Publicado', 'match' ) => __( '8 de abril, 2026', 'match' ),
		);
		$job['chips']   = array_filter( array( $job['type'] ? explode( ',', $job['type'] )[0] : '', $job['place'], str_replace( ' /mes', '', $job['salary'] ) ) );
		$job['closed']  = false;
		$job['is_demo'] = true;
		return $job;
	}

	$post_id = (int) $id;
	if ( ! $post_id || ! match_job_board_active() || MJB_Helpers::job_post_type() !== get_post_type( $post_id ) || 'publish' !== get_post_status( $post_id ) ) {
		return null;
	}

	$job      = match_job_data( $post_id );
	$benefits = (string) MJB_Helpers::get_meta( $post_id, 'benefits' );
	$deadline = (string) MJB_Helpers::get_meta( $post_id, 'deadline' );
	$city     = (string) MJB_Helpers::get_meta( $post_id, 'city' );
	$country  = (string) MJB_Helpers::get_meta( $post_id, 'country' );

	$job['description_html'] = apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) );
	$job['benefits_html']    = $benefits ? wp_kses_post( wpautop( $benefits ) ) : '';
	$job['requirements']     = array(); // El CRM lo incluye dentro de la descripción.
	$job['summary']          = array_filter(
		array(
			__( 'Nivel', 'match' )         => (string) MJB_Helpers::get_meta( $post_id, 'seniority' ),
			__( 'Modalidad', 'match' )     => MJB_Helpers::work_mode_label( (string) MJB_Helpers::get_meta( $post_id, 'work_mode' ) ),
			__( 'Jornada', 'match' )       => MJB_Helpers::employment_type_label( (string) MJB_Helpers::get_meta( $post_id, 'employment_type' ) ),
			__( 'Ubicación', 'match' )     => trim( implode( ', ', array_filter( array( $city, $country ) ) ), ', ' ),
			__( 'Salario', 'match' )       => $job['salary'],
			__( 'Publicado', 'match' )     => get_the_date( 'j \d\e F, Y', $post_id ),
			__( 'Postula hasta', 'match' ) => $deadline ? date_i18n( 'j \d\e F, Y', strtotime( $deadline ) ) : '',
		),
		fn( $v ): bool => '' !== trim( (string) $v )
	);
	$job['chips']   = array_filter( array( $job['type'], $job['place'], $job['salary'] ) );
	$job['closed']  = 'published' !== (string) MJB_Helpers::get_meta( $post_id, 'status', 'published' );
	$job['is_demo'] = false;

	return $job;
}

/* ======================================================================
 * Listado de vacantes (Figma: Job-Board/Entrance, 4009:3081)
 * ====================================================================== */

/**
 * Consulta del listado: filtros por taxonomía, búsqueda libre, orden y
 * paginación. En modo demo trabaja en memoria sobre las filas de ejemplo.
 *
 * @return array{jobs:array,total:int,pages:int,page:int,demo:bool}
 */
function match_jobs_archive_query( array $filters, string $q = '', string $order = 'reciente', int $page = 1, int $per_page = 10 ): array {
	$page = max( 1, $page );

	if ( match_jobs_demo_mode() ) {
		$jobs = match_jobs_demo_filtered( $filters );
		if ( '' !== $q ) {
			$needle = mb_strtolower( $q );
			$jobs   = array_values( array_filter( $jobs, fn( array $job ): bool => false !== mb_strpos( mb_strtolower( $job['title'] . ' ' . $job['company'] . ' ' . implode( ' ', $job['skills'] ) ), $needle ) ) );
		}
		if ( 'antiguo' === $order ) {
			$jobs = array_reverse( $jobs );
		}
		$total = count( $jobs );
		return array(
			'jobs'  => array_slice( $jobs, ( $page - 1 ) * $per_page, $per_page ),
			'total' => $total,
			'pages' => max( 1, (int) ceil( $total / $per_page ) ),
			'page'  => $page,
			'demo'  => true,
		);
	}

	$query = new WP_Query(
		array(
			'post_type'      => MJB_Helpers::job_post_type(),
			'posts_per_page' => $per_page,
			'paged'          => $page,
			's'              => $q,
			'orderby'        => 'date',
			'order'          => 'antiguo' === $order ? 'ASC' : 'DESC',
			'tax_query'      => match_jobs_tax_query( $filters ), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		)
	);

	return array(
		'jobs'  => array_map( fn( WP_Post $post ): array => match_job_data( $post->ID ), $query->posts ),
		'total' => (int) $query->found_posts,
		'pages' => max( 1, (int) $query->max_num_pages ),
		'page'  => $page,
		'demo'  => false,
	);
}
