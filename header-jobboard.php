<?php
/**
 * Cabecera del Job Board: documento sin la barra ni el pie del sitio.
 * Se usa con get_header( 'jobboard' ) en template-jobboard.php y
 * template-login.php.
 */
defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Saltar al contenido', 'match' ); ?></a>
