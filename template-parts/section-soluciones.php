<?php
/**
 * Acordeón de soluciones (Figma: node 4618:5140).
 *
 * Contenido editorial fijo. Cada panel define su foto y el tono del degradado
 * que la cubre (--overlay) y el color de la etiqueta (--label).
 */
defined( 'ABSPATH' ) || exit;

$soluciones = array(
	array(
		'slug'    => 'executive',
		'kicker'  => __( 'Headhunting', 'match' ),
		'title'   => __( 'Executive', 'match' ),
		'text'    => __( 'Búsqueda y atracción de ejecutivos de primer nivel para posiciones de alta dirección. Identificamos perfiles que no están buscando, pero que sí deberías conocer.', 'match' ),
		'overlay' => 'rgba(15,11,89,0.81)',
		'label'   => '',
	),
	array(
		'slug'    => 'assessment',
		'kicker'  => __( 'Evaluación', 'match' ),
		'title'   => __( 'Assessment', 'match' ),
		'text'    => __( 'Evaluamos competencias, potencial y ajuste cultural con metodologías propias para que cada decisión de talento se tome con evidencia.', 'match' ),
		'overlay' => 'rgba(255,255,255,0.56)',
		'label'   => 'var(--match-neutral-500)',
	),
	array(
		'slug'    => 'outplacement',
		'kicker'  => __( 'Transición', 'match' ),
		'title'   => __( 'Outplacement', 'match' ),
		'text'    => __( 'Acompañamos a los profesionales en su transición de carrera con un programa que protege la reputación de la empresa y el futuro de las personas.', 'match' ),
		'overlay' => 'rgba(15,11,89,0.47)',
		'label'   => '',
	),
	array(
		'slug'    => 'personnel',
		'kicker'  => __( 'Selección', 'match' ),
		'title'   => __( 'Personnel', 'match' ),
		'text'    => __( 'Selección de mandos medios y posiciones especializadas con la misma rigurosidad de un proceso ejecutivo.', 'match' ),
		'overlay' => 'rgba(138,56,245,0.32)',
		'label'   => '',
	),
);
?>
<section class="match-soluciones" id="soluciones">
	<?php
	match_section_head(
		__( 'Nuestras soluciones', 'match' ),
		__( 'Conoce nuestras soluciones', 'match' ),
		__( 'y encuentra la que necesitas', 'match' )
	);
	?>

	<div class="match-accordion">
		<?php foreach ( $soluciones as $index => $item ) : ?>
			<?php
			$style = '--overlay:' . $item['overlay'] . ( $item['label'] ? ';--label:' . $item['label'] : '' );
			?>
			<article class="match-accordion__panel<?php echo 0 === $index ? ' is-open' : ''; ?>" id="sol-<?php echo esc_attr( $item['slug'] ); ?>" style="<?php echo esc_attr( $style ); ?>">
				<div class="match-accordion__visual">
					<img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/solucion-' . $item['slug'] . '.webp' ) ); ?>" alt="" loading="lazy" decoding="async">
				</div>

				<button class="match-accordion__trigger" type="button" aria-expanded="<?php echo 0 === $index ? 'true' : 'false'; ?>" aria-controls="sol-body-<?php echo esc_attr( $item['slug'] ); ?>">
					<?php echo esc_html( $item['title'] ); ?>
				</button>

				<div class="match-accordion__content" id="sol-body-<?php echo esc_attr( $item['slug'] ); ?>">
					<div class="match-accordion__copy">
						<p class="match-accordion__kicker">/ <?php echo esc_html( $item['kicker'] ); ?></p>
						<h3 class="match-accordion__title"><?php echo esc_html( $item['title'] ); ?></h3>
						<p class="match-accordion__text"><?php echo esc_html( $item['text'] ); ?></p>
					</div>
					<a class="match-btn match-btn--primary" href="<?php echo esc_url( home_url( '/soluciones/' . $item['slug'] . '/' ) ); ?>">
						<?php esc_html_e( 'Más información', 'match' ); ?>
						<span class="match-btn__icon"><?php echo match_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					</a>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</section>
