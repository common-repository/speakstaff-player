<div class="wrap">
	<h2>Speakstaff Einstellungen</h2>

	<form method="post" action="options.php">
		<?php settings_fields( 'spstaSat_setPluginSettings' ); ?>
		<?php do_settings_sections( 'spstaSat_setPluginSettings' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Standard-Farbe Play Button (Color)</th>
				<td><input type="text" name="spstaSat_buttonColor" value="<?php echo esc_attr( get_option('spstaSat_buttonColor') ); ?>" class="color-field" /></td>
			</tr>
		</table>
		<input type="hidden" name="action" value="update" />
		<?php submit_button(); ?>
	</form>
</div>
