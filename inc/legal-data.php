<?php
/**
 * Contenido de las páginas legales (Términos de Servicio, Política de
 * Privacidad).
 *
 * Se editan con el grupo PCF "Legal — Contenido" (pcf-json/group_match_legal.json),
 * en cualquier página con template-legal.php. match_legal_data() trae el
 * texto fijo por slug (respaldo mientras el repeater "sections" esté vacío);
 * usa los datos de contacto del Personalizador (ver match_customize_register()
 * en functions.php) para no repetir la razón social y el RUC a mano.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Texto fijo de una página legal por slug; null si no existe.
 */
function match_legal_data( string $slug ): ?array {
	$razon = get_theme_mod( 'match_razon_social', 'MATCH CONSULTORES SAC' );
	$ruc   = get_theme_mod( 'match_ruc', '20508540788' );
	$email = get_theme_mod( 'match_email', 'conversemos@match.win' );

	$data = array(
		'terminos-de-servicio' => array(
			'title'      => __( 'Términos de Servicio', 'match' ),
			'updated_at' => __( 'Setiembre de 2026', 'match' ),
			'intro'      => sprintf(
				/* translators: 1: razón social, 2: RUC */
				__( 'Estos Términos de Servicio regulan el uso del sitio web y el Job Board de %1$s (RUC %2$s). Al registrarte, postular a una vacante o usar cualquiera de nuestros servicios, aceptas estos términos.', 'match' ),
				$razon,
				$ruc
			),
			'sections'   => array(
				array(
					'heading' => __( '1. Aceptación de los términos', 'match' ),
					'body'    => '<p>' . esc_html__( 'Al acceder o usar nuestro sitio, crear una cuenta o postular a una vacante a través del Job Board, aceptas quedar vinculado por estos Términos de Servicio y por nuestra Política de Privacidad. Si no estás de acuerdo, no debes usar el sitio.', 'match' ) . '</p>',
				),
				array(
					'heading' => __( '2. Descripción del servicio', 'match' ),
					'body'    => '<p>' . esc_html__( 'Conectamos a profesionales con oportunidades laborales publicadas por empresas clientes, y ofrecemos servicios de headhunting, evaluación de talento y outplacement. No garantizamos la obtención de un puesto ni la veracidad de cada vacante publicada por terceros, aunque revisamos la información que recibimos.', 'match' ) . '</p>',
				),
				array(
					'heading' => __( '3. Registro de cuenta', 'match' ),
					'body'    => '<ul><li>' . esc_html__( 'Debes brindar información verdadera y mantenerla actualizada.', 'match' ) . '</li><li>' . esc_html__( 'Eres responsable de la confidencialidad de tu contraseña y de la actividad realizada desde tu cuenta.', 'match' ) . '</li><li>' . esc_html__( 'Podemos suspender o cerrar cuentas que incumplan estos términos.', 'match' ) . '</li></ul>',
				),
				array(
					'heading' => __( '4. Uso del Job Board', 'match' ),
					'body'    => '<p>' . esc_html__( 'El Job Board es de uso personal y gratuito para las personas que buscan empleo. Está prohibido publicar información falsa, suplantar a otra persona o empresa, o usar el sitio para fines distintos a la búsqueda y postulación a oportunidades laborales.', 'match' ) . '</p>',
				),
				array(
					'heading' => __( '5. Contenido y conducta del usuario', 'match' ),
					'body'    => '<p>' . esc_html__( 'Al subir tu CV, foto de perfil o cualquier otro contenido, declaras que tienes los derechos necesarios sobre ese contenido y nos autorizas a compartirlo con las empresas a las que postules, con el único fin de evaluar tu candidatura.', 'match' ) . '</p>',
				),
				array(
					'heading' => __( '6. Propiedad intelectual', 'match' ),
					'body'    => '<p>' . sprintf(
						/* translators: %s: razón social */
						esc_html__( 'El diseño, las marcas, los textos y el software del sitio son propiedad de %s o de sus licenciantes. No está permitido reproducirlos o distribuirlos sin autorización previa por escrito.', 'match' ),
						esc_html( $razon )
					) . '</p>',
				),
				array(
					'heading' => __( '7. Enlaces a terceros', 'match' ),
					'body'    => '<p>' . esc_html__( 'El sitio puede enlazar a páginas de empresas clientes o de terceros. No somos responsables del contenido ni de las prácticas de privacidad de esos sitios.', 'match' ) . '</p>',
				),
				array(
					'heading' => __( '8. Limitación de responsabilidad', 'match' ),
					'body'    => '<p>' . esc_html__( 'Prestamos el servicio "tal cual" y con la mejor diligencia posible, pero no somos responsables por decisiones de contratación de las empresas, ni por daños indirectos derivados del uso del sitio.', 'match' ) . '</p>',
				),
				array(
					'heading' => __( '9. Modificaciones', 'match' ),
					'body'    => '<p>' . esc_html__( 'Podemos actualizar estos términos en cualquier momento. Los cambios entran en vigencia al publicarse en esta página, junto con la fecha de última actualización.', 'match' ) . '</p>',
				),
				array(
					'heading' => __( '10. Ley aplicable', 'match' ),
					'body'    => '<p>' . esc_html__( 'Estos términos se rigen por las leyes de la República del Perú. Cualquier controversia se someterá a los jueces y tribunales competentes de Lima.', 'match' ) . '</p>',
				),
				array(
					'heading' => __( '11. Contacto', 'match' ),
					'body'    => '<p>' . sprintf(
						/* translators: %s: correo de contacto */
						esc_html__( 'Si tienes dudas sobre estos términos, escríbenos a %s.', 'match' ),
						'<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>'
					) . '</p>',
				),
			),
		),

		'politica-de-privacidad' => array(
			'title'      => __( 'Política de Privacidad', 'match' ),
			'updated_at' => __( 'Setiembre de 2026', 'match' ),
			'intro'      => sprintf(
				/* translators: 1: razón social, 2: RUC */
				__( 'En %1$s (RUC %2$s) respetamos tu privacidad. Esta política explica qué datos personales recopilamos, para qué los usamos y qué derechos tienes sobre ellos.', 'match' ),
				$razon,
				$ruc
			),
			'sections'   => array(
				array(
					'heading' => __( '1. Responsable del tratamiento', 'match' ),
					'body'    => '<p>' . sprintf(
						/* translators: 1: razón social, 2: RUC, 3: correo de contacto */
						esc_html__( 'El responsable del tratamiento de tus datos personales es %1$s, RUC %2$s. Para cualquier consulta sobre tus datos, puedes escribir a %3$s.', 'match' ),
						esc_html( $razon ),
						esc_html( $ruc ),
						'<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>'
					) . '</p>',
				),
				array(
					'heading' => __( '2. Datos que recopilamos', 'match' ),
					'body'    => '<ul><li>' . esc_html__( 'Datos de contacto: nombre, correo electrónico, teléfono.', 'match' ) . '</li><li>' . esc_html__( 'Datos profesionales: CV, experiencia laboral, formación y pretensión salarial.', 'match' ) . '</li><li>' . esc_html__( 'Datos de uso del sitio, recogidos mediante cookies y herramientas de analítica.', 'match' ) . '</li></ul>',
				),
				array(
					'heading' => __( '3. Finalidad del tratamiento', 'match' ),
					'body'    => '<p>' . esc_html__( 'Usamos tus datos para gestionar tu postulación a vacantes, evaluar tu perfil frente a los requerimientos de nuestras empresas clientes, contactarte sobre procesos de selección y mejorar nuestros servicios.', 'match' ) . '</p>',
				),
				array(
					'heading' => __( '4. Base legal', 'match' ),
					'body'    => '<p>' . esc_html__( 'Tratamos tus datos con base en tu consentimiento, otorgado al crear tu cuenta o subir tu CV, y en nuestro interés legítimo de ofrecer un servicio de intermediación laboral.', 'match' ) . '</p>',
				),
				array(
					'heading' => __( '5. Conservación de los datos', 'match' ),
					'body'    => '<p>' . esc_html__( 'Conservamos tu información mientras tu cuenta esté activa o mientras sea necesaria para los fines descritos en esta política, salvo que solicites su eliminación antes.', 'match' ) . '</p>',
				),
				array(
					'heading' => __( '6. Tus derechos', 'match' ),
					'body'    => '<p>' . esc_html__( 'Puedes acceder, rectificar, cancelar u oponerte al tratamiento de tus datos personales (derechos ARCO) en cualquier momento, escribiéndonos a través de nuestro correo de contacto.', 'match' ) . '</p>',
				),
				array(
					'heading' => __( '7. Compartición de datos con terceros', 'match' ),
					'body'    => '<p>' . esc_html__( 'Compartimos tu CV y datos de postulación únicamente con las empresas a cuyas vacantes postulas. No vendemos tus datos personales a terceros.', 'match' ) . '</p>',
				),
				array(
					'heading' => __( '8. Seguridad de la información', 'match' ),
					'body'    => '<p>' . esc_html__( 'Aplicamos medidas técnicas y organizativas razonables para proteger tus datos personales contra accesos no autorizados, pérdida o alteración.', 'match' ) . '</p>',
				),
				array(
					'heading' => __( '9. Cookies', 'match' ),
					'body'    => '<p>' . esc_html__( 'Usamos cookies propias y de terceros para recordar tus preferencias y analizar el uso del sitio. Puedes deshabilitarlas desde la configuración de tu navegador.', 'match' ) . '</p>',
				),
				array(
					'heading' => __( '10. Cambios en esta política', 'match' ),
					'body'    => '<p>' . esc_html__( 'Podemos actualizar esta política periódicamente. Publicaremos cualquier cambio en esta misma página, junto con la fecha de última actualización.', 'match' ) . '</p>',
				),
				array(
					'heading' => __( '11. Contacto', 'match' ),
					'body'    => '<p>' . sprintf(
						/* translators: %s: correo de contacto */
						esc_html__( 'Para ejercer tus derechos o resolver dudas sobre esta política, escríbenos a %s.', 'match' ),
						'<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>'
					) . '</p>',
				),
			),
		),
	);

	return $data[ $slug ] ?? null;
}

/**
 * Datos de una página legal: los campos PCF si tiene secciones cargadas; si
 * no, el texto fijo de match_legal_data() según el slug, con "Términos de
 * Servicio" como respaldo si el slug no coincide con ninguno de los dos.
 */
function match_legal( int $post_id ): array {
	$slug     = (string) get_post_field( 'post_name', $post_id );
	$fallback = match_legal_data( $slug ) ?? match_legal_data( 'terminos-de-servicio' );

	if ( ! function_exists( 'get_field' ) || empty( get_field( 'sections', $post_id ) ) ) {
		return $fallback;
	}

	return array(
		'title'      => get_the_title( $post_id ),
		'updated_at' => (string) get_field( 'updated_at', $post_id ),
		'intro'      => (string) get_field( 'intro', $post_id ),
		'sections'   => array_map(
			static fn( $row ) => array(
				'heading' => (string) ( $row['heading'] ?? '' ),
				'body'    => (string) ( $row['body'] ?? '' ),
			),
			get_field( 'sections', $post_id )
		),
	);
}
