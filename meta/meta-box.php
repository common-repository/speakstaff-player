<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if (!is_array($meta)) {
	// bitte hier alle neuen Formularfelder eintragen
	$meta = array(
		'hash' => '',
		'trackId' => '',
		'color' => '',
		'sprecherId' => '',
		'sprecherURL' => '',
		'ss_shortlink' => '',
		'autoPlay' => '',
		'width' => '',
		'position' => '',
	);
}

?>
<div class="spstaSat_meta_box">
    <?php
    	// create a custom nonce for submit verification later
		echo '<input type="hidden" name="spstaSat_meta_noncename" value="' . wp_create_nonce('spstaSat_metabox') . '" />';
    ?>
    <p><?php _e('Bindet SpeakStaff Player in den Artikel ein', 'speakstaffSatellit'); ?></p> 	
	<label><?php _e('Soundcloud-ID (aus <a href="https://soundcloud.com/speakstaff" target="_blank">SC iFrame</a>)', 'speakstaffSatellit'); ?></label>
    <p>
        <input type="text" name="_spstaSat_meta[trackId]" value="<?php if(!empty($meta['trackId'])) echo $meta['trackId']; ?>"/>
    </p>
    <label><?php _e('QR-Shortlink (siehe <a href="http://speakstaff.com/themen/" target="_blank">speakstaff.com)</a>'); ?></label>
    <p>
        <input type="text" name="_spstaSat_meta[ss_shortlink]" value="<?php if(!empty($meta['ss_shortlink'])) echo $meta['ss_shortlink']; ?>"/>
    </p>
    <label><?php _e('Sprecher-ID + URL (siehe <a target="_blank" href="https://docs.google.com/spreadsheets/d/1EnJpt5yIdNMqH34UYMaIEAgzwv6cgcqVVVbEE_eJ_5c/">Google Docs</a>)', 'speakstaffSatellit'); ?></label>
    <p>
        <input class="ss_input sprecherId" placeholder="10" type="text" name="_spstaSat_meta[sprecherId]" value="<?php if(!empty($meta['sprecherId'])) echo $meta['sprecherId']; ?>"/>
        <input class="ss_input sprecherURL" placeholder="albert_b" type="text" name="_spstaSat_meta[sprecherURL]" value="<?php if(!empty($meta['sprecherURL'])) echo $meta['sprecherURL']; ?>"/>
    </p>
    <div class="ss_inputgroup">
	    <div class="ss_inputgroup_item ss_autoplay">
    <label class="block"><?php _e('Auto-Play?', 'speakstaffSatellit'); ?></label> 
    <p>
		<input  type="radio" name="_spstaSat_meta[autoPlay]" value="false"<?php if($meta['autoPlay'] == 'false') echo ' checked="checked"'; ?>><?php _e('Nein1', 'speakstaffSatellit'); ?> 
        <input type="radio" name="_spstaSat_meta[autoPlay]" value="true"<?php if($meta['autoPlay'] == 'true') echo ' checked="checked"'; ?>><?php _e('Ja', 'speakstaffSatellit'); ?> 
    </p>
	    </div>
	    <div class="ss_inputgroup_item ss_playcolor"><label><?php _e('Farbe Play-Button', 'speakstaffSatellit'); ?></label>
			<p>
			<input type="text" name="_spstaSat_meta[color]" value="<?php if(!empty($meta['color'])) echo '#'.$meta['color']; ?>" class="color-field" />
    		</p> 
	    </div>
    </div>
    <div class="ss_inputgroup">
	    <div class="ss_inputgroup_item ss_playerwidth">
	<label><?php _e('Player Breite', 'speakstaffSatellit'); ?></label>
    <p>
		<select name="_spstaSat_meta[width]">
			<?php
				if(!strlen($meta['width'])) $meta['width'] = '80';
				$i = 40;
				do {
					$selected = '';
					if($meta['width'] == $i) $selected = ' selected="selected"';
					
					echo '<option value="' . $i . '"' . $selected .'>' . $i . '%</option>';
					$i += 10;
				} while ($i <= 90);
			?>			
		</select>
    </p>
	    </div>
	    <div class="ss_inputgroup_item ss_playerpos">
	<label><?php _e('Player Position', 'speakstaffSatellit'); ?></label>
    <p>
		<select name="_spstaSat_meta[position]">
			<?php
				$positions = array(
					'top' => __('oben', 'speakstaffSatellit'),
					'bottom' => __('unten', 'speakstaffSatellit'),
					'both' => __('oben und unten', 'speakstaffSatellit'),
					'no' => __('per Shortcode', 'speakstaffSatellit')
				);
				if(!strlen($meta['position'])) $meta['position'] = 'no';				
				foreach($positions as $key => $value) {
					$selected = '';
					if($meta['position'] === $key) $selected = ' selected="selected"';
					echo '<option value="' . $key . '"' . $selected .'>' . $value . '</option>';
				}
			?>			
		</select>
    </p>
	    </div>
    </div>
</div>