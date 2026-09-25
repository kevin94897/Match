<?php
/**
 * Template Name: Design System
 *
 * Guía visual de los tokens y componentes base del tema. Sirve para revisar
 * la maqueta contra Figma sin navegar por todo el sitio.
 */
defined( 'ABSPATH' ) || exit;

get_header();

$palettes = array(
	__( 'Marca', 'match' )        => array( 'primario-50', 'primario-400', 'primario-500', 'primario-800', 'violeta', 'celeste-500', 'celeste-600', 'celeste-700', 'celeste-800' ),
	__( 'Azul neutral', 'match' ) => array( 'azul-50', 'azul-100', 'azul-200', 'azul-300', 'azul-400', 'azul-500', 'azul-600', 'azul-700', 'azul-800', 'azul-900' ),
	__( 'Neutros', 'match' )      => array( 'neutral-100', 'neutral-200', 'neutral-300', 'neutral-400', 'neutral-500', 'negro', 'blanco' ),
	__( 'Semánticos', 'match' )   => array( 'exito', 'alerta', 'error' ),
);

$type = array(
	array( 'Heading / H2-Large', 'Open Sauce Sans 700 · 110 / 1.2', 'match-h2-xl', 'Vacantes.' ),
	array( 'Heading / H2', 'Open Sauce Sans 700 · 56 / 1.2', 'match-h2', 'Empresas que confiaron en Match' ),
	array( 'Heading / H4', 'Poppins 600 · 24 / 1.4', 'match-h4', 'Gerente de operaciones logísticas' ),
	array( 'Body / xxl', 'Poppins 500 · 20 / 1.5', 'match-xxl', 'Conoce nuestras soluciones' ),
	array( 'Body / Largo', 'Poppins 500 · 18 / 1.6', 'match-body-lg', 'Sé parte de la base de datos ejecutiva más importante de la región.' ),
	array( 'Body / Regular', 'Poppins 400 · 16 / 1.6', 'match-body', 'Las empresas líderes nos confían sus búsquedas.' ),
	array( 'Nav / Regular', 'Poppins 500 · 15 / 1.7', 'match-nav-text', 'Posiciones disponibles' ),
	array( 'Body / small', 'Poppins 400 · 13 / 1', 'match-body-sm', 'Publicado hace 3 días' ),
	array( 'Otros / Eyebrow', 'Poppins 500 · 16 / 1.5', 'match-eyebrow', 'Bienvenido a Match' ),
);

$spaces = array( 4, 8, 12, 16, 24, 32, 40, 48, 56, 64, 80, 120 );
$radii  = array( 4, 8, 12, 16, 24 );
$icons  = array( 'user', 'chevron', 'arrow', 'phone', 'mail' );
?>
<div class="match-container match-sg">

	<section class="match-sg__section">
		<h2 class="match-sg__title"><?php esc_html_e( 'Color', 'match' ); ?></h2>
		<p class="match-sg__note"><?php esc_html_e( 'Variables --match-* definidas en assets/css/tokens.css. El plugin del Job Board consume los mismos nombres.', 'match' ); ?></p>
		<?php foreach ( $palettes as $group => $names ) : ?>
			<p class="match-sg__group"><?php echo esc_html( $group ); ?></p>
			<div class="match-sg__swatches">
				<?php foreach ( $names as $name ) : ?>
					<div class="match-sg__swatch">
						<div class="match-sg__swatch-color" style="background: var(--match-<?php echo esc_attr( $name ); ?>)"></div>
						<dl><dt><?php echo esc_html( $name ); ?></dt><dd>--match-<?php echo esc_html( $name ); ?></dd></dl>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
	</section>

	<section class="match-sg__section">
		<h2 class="match-sg__title"><?php esc_html_e( 'Tipografía', 'match' ); ?></h2>
		<p class="match-sg__note"><?php esc_html_e( 'Open Sauce Sans solo en titulares grandes; Poppins para todo lo demás.', 'match' ); ?></p>
		<div class="match-sg__type">
			<?php foreach ( $type as list( $label, $spec, $class, $sample ) ) : ?>
				<div class="match-sg__type-row">
					<div class="match-sg__type-meta"><?php echo esc_html( $label ); ?><br><?php echo esc_html( $spec ); ?></div>
					<p class="<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $sample ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="match-sg__section">
		<h2 class="match-sg__title"><?php esc_html_e( 'Spacing y radios', 'match' ); ?></h2>
		<p class="match-sg__group"><?php esc_html_e( 'Spacing', 'match' ); ?></p>
		<div class="match-sg__spacing">
			<?php foreach ( $spaces as $n ) : ?>
				<div class="match-sg__space"><span style="--size: var(--match-space-<?php echo (int) $n; ?>)"></span><?php echo (int) $n; ?></div>
			<?php endforeach; ?>
		</div>
		<p class="match-sg__group" style="margin-block-start: var(--match-space-32)"><?php esc_html_e( 'Radios', 'match' ); ?></p>
		<div class="match-sg__radii">
			<?php foreach ( $radii as $n ) : ?>
				<div class="match-sg__radius" style="border-radius: var(--match-radius-<?php echo (int) $n; ?>)"><?php echo (int) $n; ?></div>
			<?php endforeach; ?>
			<div class="match-sg__radius" style="border-radius: var(--match-radius-full)">full</div>
		</div>
	</section>

	<section class="match-sg__section">
		<h2 class="match-sg__title"><?php esc_html_e( 'Botones', 'match' ); ?></h2>
		<div class="match-sg__row">
			<a class="match-btn match-btn--primary" href="#">Encuentra tu próximo puesto</a>
			<a class="match-btn match-btn--secondary" href="#">Iniciar sesión <span class="match-btn__icon"><?php echo match_icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></a>
			<a class="match-btn match-btn--secondary" href="#">Postular ahora <span class="match-btn__icon"><?php echo match_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></a>
			<button class="match-btn match-btn--primary" disabled>Deshabilitado</button>
		</div>
		<div class="match-sg__row match-sg__row--dark">
			<button class="match-btn match-btn--round" type="button" aria-label="Expandir">+</button>
			<button class="match-btn match-btn--round is-active" type="button" aria-label="Contraer">−</button>
			<span class="match-pill">Marketing</span>
			<span class="match-pill">Full time</span>
			<button class="match-pill match-pill--filter" type="button">Ubicación <?php echo match_icon( 'chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
		</div>
	</section>

	<section class="match-sg__section">
		<h2 class="match-sg__title"><?php esc_html_e( 'Tarjetas', 'match' ); ?></h2>
		<div class="match-sg__row match-sg__row--photo">
			<div class="match-glass" style="width: min(380px, 100%); text-align: center">
				<p class="match-h4" style="margin: 0 0 var(--match-space-8); font-size: 21px">Da el primer paso</p>
				<p style="margin: 0 0 var(--match-space-24); font-size: var(--match-fs-nav); color: rgba(255,255,255,.72)">Accede a las posiciones más exclusivas del mercado ejecutivo</p>
				<a class="match-btn match-btn--primary" href="#"><span class="match-btn__orbit" aria-hidden="true"></span>Encuentra tu próximo puesto</a>
			</div>
		</div>
		<div class="match-sg__row">
			<div class="match-card" style="max-width: 534px">
				<p class="match-h4" style="margin: 0 0 var(--match-space-16)">“Tengo el mejor de los conceptos del servicio de Match”</p>
				<p style="margin: 0; color: var(--match-azul-400)">Cristopher Cordano · Global Project Manager</p>
			</div>
		</div>
	</section>

	<section class="match-sg__section">
		<h2 class="match-sg__title"><?php esc_html_e( 'Iconos y logo', 'match' ); ?></h2>
		<div class="match-sg__icons" style="margin-block-end: var(--match-space-32)">
			<?php foreach ( $icons as $icon ) : ?>
				<div class="match-sg__icon"><?php echo match_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $icon ); ?></div>
			<?php endforeach; ?>
		</div>
		<div class="match-sg__row"><div class="match-sg__logo"><?php echo match_logo(); // phpcs:ignore WordPress.Security.EscapeOutput ?></div></div>
		<div class="match-sg__row match-sg__row--dark"><div class="match-sg__logo match-sg__logo--light"><?php echo match_logo(); // phpcs:ignore WordPress.Security.EscapeOutput ?></div></div>
	</section>

</div>
<?php
get_footer();
