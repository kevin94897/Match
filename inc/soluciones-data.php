<?php
/**
 * Contenido de las internas de solución.
 *
 * Por ahora es fijo (Figma: Executive, node 3232:4955). Cuando el cliente
 * confirme los textos de Assessment, Outplacement y Personnel se agregan
 * aquí o se migran a campos del editor.
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
	);

	return $data[ $slug ] ?? null;
}
