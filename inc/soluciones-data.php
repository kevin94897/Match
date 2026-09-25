<?php
/**
 * Contenido de las internas de solución.
 *
 * Se edita en cada página con el grupo PCF "Solución — Contenido"
 * (pcf-json/group_match_solucion.json), cuyos campos usan estas mismas claves.
 * Los datos fijos de abajo (Figma: Executive 3232:4955, Outplacement 3280:450)
 * son el respaldo para páginas cuyos campos aún están vacíos.
 *
 * Claves por solución:
 * - theme         Sufijo de la clase match-sol--{theme} en <body>; activa la
 *                 paleta en assets/css/solucion.css.
 * - logo          Ruta absoluta del SVG del lockup "match + solución".
 * - levels_title  Titular de la sección de perfiles/audiencias, en dos líneas.
 * - levels_icon   Icono opcional en la esquina de cada tarjeta de nivel.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Datos de una solución por slug; null si no existe.
 */
function match_solucion_data( string $slug ): ?array {
	$img = fn( string $file ): string => get_theme_file_uri( 'assets/img/' . $file );

	$data = array(
		'executive' => array(
			'title'   => 'Executive',
			'theme'   => 'executive',
			'logo'    => get_theme_file_path( 'assets/img/executive/logo-executive.svg' ),
			'kicker'  => __( 'Headhunting', 'match' ),
			'lead'    => __( 'No usamos avisos.', 'match' ),
			'lead_2'  => __( 'Vamos directo a buscar al líder que ya está trabajando en tu competencia — mapeo directo, sin publicaciones ni intermediarios.', 'match' ),
			'trust'   => array( 'abb', 'olx', 'elektra', 'alfa-laval', 'intercorp' ),
			'metrics' => array(
				array( '91%', __( 'Top performers colocados', 'match' ) ),
				array( '24', __( 'Horas récord en contratación', 'match' ) ),
				array( '100%', __( 'Efectividad en primera terna', 'match' ) ),
				array( '60', __( 'Minutos en presentar candidatos', 'match' ) ),
			),
			'metrics_photo' => $img( 'executive/metricas.webp' ),
			'cases'   => array(
				array(
					'stats'   => array(
						array( __( '21 días', 'match' ), __( 'para la terna final', 'match' ) ),
						array( __( '3 de 3', 'match' ), __( 'candidatos en entrevista final', 'match' ) ),
						array( '100%', __( 'permanencia al año', 'match' ) ),
					),
					'quote'   => __( '“Necesitábamos un Global Project Manager que no estaba en el mercado. Match lo encontró, lo convenció y hoy lidera nuestra operación regional.”', 'match' ),
					'text'    => __( 'Era una posición cerrada, con un perfil técnico-comercial muy específico y candidatos que no estaban buscando cambio. Presentamos la terna en tres semanas.', 'match' ),
					'role'    => __( 'Global Project Manager', 'match' ),
					'company' => __( 'Alfa Laval · Industrial', 'match' ),
					'logo'    => $img( 'executive/logo-alfa-laval-blanco.png' ),
				),
				array(
					'stats'   => array(),
					'quote'   => __( '“Match funciona bien en momentos muy críticos. Fue de gran soporte con posiciones estratégicas del área técnica comercial.”', 'match' ),
					'text'    => __( 'Su trabajo fue vital para que el negocio funcione. Lo hicieron súper rápido y bastante acertado.', 'match' ),
					'role'    => __( 'Director de Operaciones', 'match' ),
					'company' => __( 'Uvirtual · Educación', 'match' ),
					'logo'    => '',
				),
			),
			'levels'  => array(
				array( __( 'Board Members', 'match' ), $img( 'executive/nivel-board.webp' ) ),
				array( __( 'VPs', 'match' ), $img( 'executive/nivel-vps.webp' ) ),
				array( __( 'C-Level', 'match' ), $img( 'executive/nivel-clevel.webp' ) ),
				array( __( 'Directores', 'match' ), $img( 'executive/nivel-directores.webp' ) ),
			),
			'levels_title'   => __( 'Los perfiles que', 'match' ),
			'levels_title_2' => __( 'acompañamos.', 'match' ),
			'levels_icon'    => '',
			'scope'   => __( 'Cubrimos toda la alta dirección.', 'match' ),
			'scope_2' => __( 'Desde Board Members hasta gerencias corporativas, mapeamos el talento exacto que tu organización necesita en cada nivel.', 'match' ),
			'clients' => array( 'mmg', 'alfa-laval', 'olx', 'abb', 'beat', 'intercorp' ),
			'years'   => '(2020-26)',
			'reviews' => array(
				array(
					'text'  => __( 'Tengo el mejor de los conceptos del servicio de Match. Es inclusive mucho mejor que otras consultoras trasnacionales, tienen real conocimiento del mercado. Sus candidatos mejoraron mis expectativas referente a su competencia.', 'match' ),
					'name'  => 'Christopher Candamo',
					'role'  => __( 'Global Project Manager · Alfa Laval, Suecia', 'match' ),
					'photo' => $img( 'executive/cliente-alfa-laval.png' ),
					'bg'    => '#1e1c76',
					'fit'   => 'contain',
				),
				array(
					'text'  => __( 'Match funciona bien en momentos muy críticos. Fue de gran soporte con posiciones estratégicas del área técnica comercial. Su trabajo fue vital para que el negocio funcione. Lo hicieron súper rápido y bastante acertado.', 'match' ),
					'name'  => 'Jose Chavez',
					'role'  => __( 'Director de Operaciones · Uvirtual, México', 'match' ),
					'photo' => $img( 'executive/cliente-uvirtual.png' ),
					'bg'    => '#e0e0e0',
					'fit'   => 'cover',
				),
			),
			'reviews_photo' => $img( 'executive/testimonios-dark.webp' ),
			'contact_lead'  => __( 'Cuéntanos qué necesitas — hablemos de tu próxima búsqueda de alta dirección.', 'match' ),
			'toast'   => __( '¿Buscas un perfil ejecutivo?', 'match' ),
		),

		'outplacement' => array(
			'title'   => 'Outplacement',
			'theme'   => 'outplacement',
			'logo'    => get_theme_file_path( 'assets/img/outplacement/logo-outplacement.svg' ),
			'kicker'  => __( 'Recolocación', 'match' ),
			'lead'    => __( 'Acompañamos la salida,', 'match' ),
			'lead_2'  => __( 'no solo la anunciamos. Rediseño de marca personal y entrenamiento en negociación para una recolocación rápida — individual (VIP) o corporativo (masivo).', 'match' ),
			'trust'   => array( 'abb', 'olx', 'elektra', 'alfa-laval', 'intercorp' ),
			'metrics' => array(
				array( '30', __( 'Días récord en recolocación', 'match' ) ),
				array( '7/10', __( 'Clientes mejoran su paquete salarial', 'match' ) ),
				array( '72%', __( 'Más rápidos que la competencia', 'match' ) ),
			),
			'metrics_photo' => $img( 'outplacement/metricas.webp' ),
			'cases'   => array(
				array(
					'stats'   => array(
						array( __( '21 días', 'match' ), __( 'para la terna final', 'match' ) ),
						array( __( '3 de 3', 'match' ), __( 'candidatos en entrevista final', 'match' ) ),
						array( '100%', __( 'permanencia al año', 'match' ) ),
					),
					'quote'   => __( '“Tuvimos que reducir el equipo comercial y ninguno de los doce salió resentido. Ocho ya están trabajando en algo mejor.”', 'match' ),
					'text'    => __( 'Acompañamos a cada persona con orientación individual, revisión de CV y contactos en el mercado. El proceso se cerró sin conflictos ni exposición pública.', 'match' ),
					'role'    => __( 'Directora de Gestión Humana', 'match' ),
					'company' => __( 'Empresa de consumo masivo · Perú', 'match' ),
					'logo'    => $img( 'executive/logo-alfa-laval-blanco.png' ),
				),
			),
			'levels'  => array(
				array( __( 'Para ejecutivos', 'match' ), $img( 'outplacement/audiencia-ejecutivos.webp' ) ),
				array( __( 'Para empresas', 'match' ), $img( 'outplacement/audiencia-empresas.webp' ) ),
			),
			'levels_title'   => __( 'A quién', 'match' ),
			'levels_title_2' => __( 'acompañamos.', 'match' ),
			'levels_icon'    => 'isotipo-circle',
			'scope'   => __( 'Acompañamos ambos lados de la transición.', 'match' ),
			'scope_2' => __( 'Empresas en reestructuración y profesionales que buscan su siguiente paso.', 'match' ),
			'clients' => array( 'mmg', 'alfa-laval', 'olx', 'abb', 'beat', 'intercorp' ),
			'years'   => '(2020-26)',
			'reviews' => array(
				array(
					'text'  => __( 'Tengo el mejor de los conceptos del servicio de Match. Es inclusive mucho mejor que otras consultoras trasnacionales, tienen real conocimiento del mercado. Sus candidatos mejoraron mis expectativas referente a su competencia.', 'match' ),
					'name'  => 'Christopher Candamo',
					'role'  => __( 'Global Project Manager · Alfa Laval, Suecia', 'match' ),
					'photo' => $img( 'executive/cliente-alfa-laval.png' ),
					'bg'    => '#1e1c76',
					'fit'   => 'contain',
				),
				array(
					'text'  => __( 'Match funciona bien en momentos muy críticos. Fue de gran soporte con posiciones estratégicas del área técnica comercial. Su trabajo fue vital para que el negocio funcione. Lo hicieron súper rápido y bastante acertado.', 'match' ),
					'name'  => 'Jose Chavez',
					'role'  => __( 'Director de Operaciones · Uvirtual, México', 'match' ),
					'photo' => $img( 'executive/cliente-uvirtual.png' ),
					'bg'    => '#e0e0e0',
					'fit'   => 'cover',
				),
			),
			'reviews_photo' => $img( 'executive/testimonios-dark.webp' ),
			'contact_lead'  => __( 'Cuéntanos tu caso — hablemos del programa que mejor se ajusta a tu situación.', 'match' ),
			'toast'   => __( '¿Estás reestructurando tu equipo?', 'match' ),
		),
	);

	return $data[ $slug ] ?? null;
}

/**
 * Datos de la solución de una página: los campos PCF si están llenos (el
 * kicker es obligatorio, así que sirve de indicador); si no, los fijos de
 * match_solucion_data() según el slug, con Executive como último respaldo.
 * Devuelve la misma forma que match_solucion_data().
 */
function match_solucion( int $post_id ): array {
	$slug     = (string) get_post_field( 'post_name', $post_id );
	$fallback = match_solucion_data( $slug ) ?? match_solucion_data( 'executive' );

	if ( ! function_exists( 'get_field' ) || ! get_field( 'kicker', $post_id ) ) {
		return $fallback;
	}

	$f     = static fn( string $name, $default = '' ) => get_field( $name, $post_id ) ?: $default;
	$pairs = static fn( array $rows, string $a, string $b ): array => array_map( static fn( $row ) => array( $row[ $a ] ?? '', $row[ $b ] ?? '' ), $rows );
	$theme = $f( 'theme', 'executive' );

	// El lockup SVG se inserta inline: primero el adjunto del campo, luego el
	// del tema por slug o por paleta. WordPress no permite subir SVG sin un
	// plugin que lo habilite, por eso el respaldo en el tema.
	$logo = '';
	foreach ( array( get_attached_file( (int) $f( 'logo', 0 ) ), get_theme_file_path( "assets/img/{$slug}/logo-{$slug}.svg" ), get_theme_file_path( "assets/img/{$theme}/logo-{$theme}.svg" ) ) as $path ) {
		if ( $path && str_ends_with( $path, '.svg' ) && is_readable( $path ) ) {
			$logo = $path;
			break;
		}
	}

	return array(
		'title'          => get_the_title( $post_id ),
		'theme'          => $theme,
		'logo'           => $logo ?: $fallback['logo'],
		'kicker'         => $f( 'kicker' ),
		'lead'           => $f( 'lead' ),
		'lead_2'         => $f( 'lead_2' ),
		'trust'          => $f( 'trust', array() ),
		'metrics'        => $pairs( $f( 'metrics', array() ), 'value', 'label' ),
		'metrics_photo'  => $f( 'metrics_photo' ),
		'cases'          => array_map(
			static fn( $row ) => array(
				'stats'   => $pairs( $row['stats'] ?: array(), 'value', 'label' ),
				'quote'   => $row['quote'] ?? '',
				'text'    => $row['text'] ?? '',
				'role'    => $row['role'] ?? '',
				'company' => $row['company'] ?? '',
				'logo'    => $row['logo'] ?: '',
			),
			$f( 'cases', array() )
		),
		'levels'         => $pairs( $f( 'levels', array() ), 'label', 'photo' ),
		'levels_title'   => $f( 'levels_title' ),
		'levels_title_2' => $f( 'levels_title_2' ),
		'levels_icon'    => $f( 'levels_icon' ),
		'scope'          => $f( 'scope' ),
		'scope_2'        => $f( 'scope_2' ),
		'clients'        => $f( 'clients', array() ),
		'years'          => $f( 'years' ),
		'reviews'        => array_map(
			static fn( $row ) => array(
				'text'  => $row['text'] ?? '',
				'name'  => $row['name'] ?? '',
				'role'  => $row['role'] ?? '',
				'photo' => $row['photo'] ?: '',
				'bg'    => $row['bg'] ?: '#e0e0e0',
				'fit'   => $row['fit'] ?: 'cover',
			),
			$f( 'reviews', array() )
		),
		'reviews_photo'  => $f( 'reviews_photo' ),
		'contact_lead'   => $f( 'contact_lead' ),
		'toast'          => $f( 'toast', $fallback['toast'] ),
	);
}
