<?php
/**
 * Fila del CV guardado (Figma: node 4241:3275). Sin CV muestra el vacío.
 *
 * @var array $args { cv: array|null (ver match_user_cv) }
 */
defined( 'ABSPATH' ) || exit;
$cv = $args['cv'] ?? null;
?>
<?php if ( $cv ) : ?>
	<a class="match-jb-cv" href="<?php echo esc_url( $cv['url'] ); ?>" target="_blank" rel="noopener">
		<span class="match-jb-cv__type" aria-hidden="true"><?php echo esc_html( $cv['ext'] ); ?></span>
		<span class="match-jb-cv__meta">
			<span class="match-jb-cv__name"><?php echo esc_html( $cv['name'] ); ?></span>
			<span class="match-jb-cv__info"><?php echo esc_html( trim( $cv['date'] . '  ·  ' . $cv['size'], ' ·' ) ); ?></span>
		</span>
	</a>
<?php else : ?>
	<div class="match-jb-cv match-jb-cv--empty">
		<span class="match-jb-cv__type match-jb-cv__type--empty" aria-hidden="true">—</span>
		<span class="match-jb-cv__meta">
			<span class="match-jb-cv__name"><?php esc_html_e( 'Aún no has subido tu CV', 'match' ); ?></span>
			<span class="match-jb-cv__info"><?php esc_html_e( 'PDF o Word · máx. 10 MB', 'match' ); ?></span>
		</span>
	</div>
<?php endif; ?>
