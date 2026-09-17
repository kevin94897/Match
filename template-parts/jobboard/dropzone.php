<?php
/**
 * Caja "Déjanos tu CV" del panel (Figma: DropZone — CV (Hero), node 4546:31).
 *
 * Solo la interfaz: el archivo se valida en el navegador (PDF/Word, 5 MB) y
 * el envío queda pendiente de definir con el CRM. Ver README.
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="match-jb-drop" data-dropzone>
	<span class="match-jb-drop__icon" aria-hidden="true"><?php echo match_icon( 'upload' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
	<div class="match-jb-drop__text">
		<p class="match-jb-drop__title"><?php esc_html_e( '¿No encuentras la vacante ideal?', 'match' ); ?></p>
		<p class="match-jb-drop__lead"><?php esc_html_e( 'Déjanos tu CV y te contactamos cuando aparezca una oportunidad para tu perfil.', 'match' ); ?></p>
	</div>
	<label class="match-jb__btn match-jb__btn--light match-jb-drop__btn">
		<?php esc_html_e( 'Subir CV', 'match' ); ?>
		<input class="screen-reader-text" type="file" name="cv" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" data-dropzone-input>
	</label>
	<p class="match-jb-drop__hint" data-dropzone-hint><?php esc_html_e( 'Arrastra tu archivo aquí · PDF o Word · máx. 5 MB', 'match' ); ?></p>
</div>
