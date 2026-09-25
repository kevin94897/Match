<?php
/**
 * Template Name: Legal
 *
 * Términos de Servicio y Política de Privacidad. Contenido editable con el
 * grupo PCF "Legal — Contenido" (pcf-json/group_match_legal.json); ver
 * match_legal() en inc/legal-data.php para el respaldo por slug.
 *
 * Reutiliza .match-page / .match-job__prose de page.php para verse igual que
 * cualquier otra página de contenido del tema. Con más de una sección se suma
 * un índice lateral (match-legal__toc) que resalta la sección visible al
 * hacer scroll (ver app.js).
 */
defined( 'ABSPATH' ) || exit;

$legal    = match_legal( get_queried_object_id() );
$sections = array_values( array_filter( $legal['sections'], static fn( $s ) => $s['heading'] || $s['body'] ) );

get_header();
?>
<article class="match-page">
	<div class="match-container">
		<header class="match-page__head">
			<h1 class="match-h2"><?php echo esc_html( $legal['title'] ); ?></h1>
			<?php if ( $legal['updated_at'] ) : ?>
				<p class="match-page__updated">
					<?php
					printf(
						/* translators: %s: fecha */
						esc_html__( 'Última actualización: %s', 'match' ),
						esc_html( $legal['updated_at'] )
					);
					?>
				</p>
			<?php endif; ?>
		</header>

		<div class="match-legal__layout">
			<?php if ( count( $sections ) > 1 ) : ?>
				<aside class="match-legal__toc">
					<div class="match-job__panel match-legal__toc-card">
						<p class="match-job__panel-title"><?php esc_html_e( 'En esta página', 'match' ); ?></p>
						<nav aria-label="<?php esc_attr_e( 'Índice', 'match' ); ?>" data-legal-toc>
							<ol class="match-legal__toc-list">
								<?php foreach ( $sections as $i => $section ) : ?>
									<li><a href="#sec-<?php echo (int) ( $i + 1 ); ?>" data-legal-toc-link><?php echo esc_html( $section['heading'] ); ?></a></li>
								<?php endforeach; ?>
							</ol>
						</nav>
					</div>
				</aside>
			<?php endif; ?>

			<div class="match-page__content match-job__prose match-legal__body">
				<?php if ( $legal['intro'] ) : ?>
					<p class="match-page__intro"><?php echo esc_html( $legal['intro'] ); ?></p>
				<?php endif; ?>

				<?php foreach ( $sections as $i => $section ) : ?>
					<h2 id="sec-<?php echo (int) ( $i + 1 ); ?>"><?php echo esc_html( $section['heading'] ); ?></h2>
					<?php echo wp_kses_post( $section['body'] ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</article>
<?php
get_footer();
