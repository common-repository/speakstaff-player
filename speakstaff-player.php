<?php
/**
 * @package Speakstaff-Satellit
 * @version 0.5
 */

/*
Plugin Name: Speakstaff Player
Plugin URI: http://speakstaff.com/
Description: Integrates the SpeakStaff player to your content
Version: 0.5
Author: SpeakStaff.com
Author URI: http://speakstaff.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: speakstaffSatellit
Domain Path: /languages

Speakstaff Satellit is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Speakstaff Satellit is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Speakstaff Satellit. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

defined('ABSPATH') or die('No script kiddies please!');

add_action( 'plugins_loaded', 'spstaSat_load_textdomain' );
/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function spstaSat_load_textdomain() {
  load_plugin_textdomain( 'speakstaffSatellit', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' ); 
}

add_action('admin_init', 'spstaSat_meta_init');
function spstaSat_meta_init() {
	// Add plugin stylesheet
    wp_enqueue_style('spstaSat_meta_css', plugins_url('css/', __FILE__) . '/speakstaff.css');
	
	// Add the color picker css file
    wp_enqueue_style('wp-color-picker');
	
	// Include our custom jQuery file with WordPress Color Picker dependency
	wp_enqueue_script('spstaSat_meta_js', plugins_url('js', __FILE__) . '/spstaSat_meta.js', array('wp-color-picker'), false, true);
	
	wp_localize_script( 'spstaSat_meta_js', 'spstaSatL10n', array(
		'tinyMceButton' => __('Integrate SpeakStaff Player', 'speakstaffSatellit'),
		'windowTitle' => __('SpeakStaff Player', 'speakstaffSatellit'),
		'buttonClose' => __('Close', 'speakstaffSatellit'),
		'buttonSubmit' => __('Generate Shortcode', 'speakstaffSatellit'),
		'idHelpText' => __('Paste your SpeakStaff Player ID here:', 'speakstaffSatellit'),
		'idLabel' => __('SpeakStaff ID:', 'speakstaffSatellit'),
		'settingsHelpText' => __('Advanced settings', 'speakstaffSatellit'),
		'widthLabel' => __('Player Width:', 'speakstaffSatellit'),
		'autoplayLabel' => __('Autoplay?', 'speakstaffSatellit'),
		'autoplayLabelOptionYes' => __('Yes', 'speakstaffSatellit'),
		'autoplayLabelOptionNo' => __('No', 'speakstaffSatellit')		
	));
}

function spstaSat_fe_init() {
	//wp_enqueue_style( 'style-name', get_stylesheet_uri() );
	wp_enqueue_script( 'speakstaff_connecter', plugins_url('js', __FILE__) . '/spstaSat_fe.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'spstaSat_fe_init' );
 
function spstaSat_meta_setup() {
    global $post;
  
    // from showing up in the custom fields section
    $meta = get_post_meta($post->ID, '_spstaSat_meta', TRUE);
  
    // include metabox html	
    include(plugin_dir_path(__FILE__) . 'meta/meta-box.php');
  
    // create a custom nonce for submit verification later
    echo '<input type="hidden" name="spstaSat_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}

add_action( 'save_post', 'spstaSat_meta_save' );  
function spstaSat_meta_save($post_id) {
    // authentication checks
    // make sure data came from our meta box
    if (!wp_verify_nonce($_POST['spstaSat_meta_noncename'],__FILE__)) return $post_id;
 
    // check user permissions
    if ($_POST['post_type'] == 'page') {
        if (!current_user_can('edit_page', $post_id)) return $post_id;
    } else if (!current_user_can('edit_post', $post_id)) return $post_id;
	
    $current_data = get_post_meta($post_id, '_spstaSat_meta', TRUE);  
  
    $new_data = $_POST['_spstaSat_meta'];
	$new_data['color'] = str_replace('#', '', $new_data['color']);
 
    spstaSat_meta_clean($new_data);
     
    if ($current_data) {
        if (is_null($new_data)) delete_post_meta($post_id, '_spstaSat_meta');
        else update_post_meta($post_id, '_spstaSat_meta', $new_data);
    } elseif (!is_null($new_data)) {
        add_post_meta($post_id, '_spstaSat_meta', $new_data,TRUE);
    }
    return $post_id;
}
 
function spstaSat_meta_clean(&$arr)
{
    if (is_array($arr)) {
        foreach($arr as $i => $v) {
            if (is_array($arr[$i])) {
                spstaSat_meta_clean($arr[$i]);
                if (!count($arr[$i])) unset($arr[$i]);
            } else if (trim($arr[$i]) == '') unset($arr[$i]);
        }
        if (!count($arr)) $arr = NULL;
    }
}

add_action('wp_enqueue_scripts', 'spstaSat_enqueue_style');
function spstaSat_enqueue_style() {
    // load active theme stylesheet in both cases
    wp_enqueue_style('spstaSat-style', plugins_url('css', __FILE__) . "/speakstaff-player.css", false);
}

/** OLD VERSION	 **/
add_filter('the_content', 'spstaSat_speakstaff_content');
function spstaSat_speakstaff_content($content) {
	global $post;

    $new_content = $content;
	$meta = get_post_meta($post->ID ,'_spstaSat_meta', true);
	
	if (is_single()) {
    	if (is_array($meta)) {
    	$spstaSat_content = '<h2>' . __("Artikel vorlesen lassen", "speakstaffSatellit") . '</h2>
    						   <div class="speakstaffplayer">	
    							<div class="speakstaff_related">
    								<a href="http://speakstaff.com" title="' . __("Zu SpeakStaff.com", "speakstaffSatellit") . '" target="_blank">
    									<img class="speakstaff_playericon" src="' . plugins_url('assets', __FILE__) . '" />
    								</a>
    								<small>' . __("Scan&Go:", "speakstaffSatellit") . '</small>
									<a href="http://spk.st/ab6go" target="_blank" title="' . __("Scan&Go: Scanne den Audio-Beitrag mit einem QR-Scanner und nimm ihn unterwegs zum Hören mit! Mehr Infos...", "speakstaffSatellit") . '"><img class="ss_qrcode" src="http://chart.apis.google.com/chart?chs=70x70&amp;cht=qr&amp;chld=M&amp;chl=' . $meta['ss_shortlink'] . '"></a>
        							<a target="_blank" href="http://speakstaff.com/user/' . $meta['sprecherURL'] . '">
        								<img class="speakstaff_narrator_img" src="http://speakstaff.com/wp-content/uploads/ultimatemember/' . $meta['sprecherId'] . '/profile_photo-190.jpg" />
    								</a>
    							</div>
    							<iframe class="ss_sc_player" width="' . $meta['width'] . '%" height="166" scrolling="no" frameborder="no" 
    								src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/' . $meta['trackId'] . '&amp;color=' . $meta['color'] . '&amp;auto_play=' . $meta['autoPlay'] . '&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false">
    							</iframe>
        					</div><hr />';
    	switch($meta['position']) {
    		case 'top':
    			$new_content = $spstaSat_content . $content;
    			break;
    		case 'bottom':
    			$new_content = $content . $spstaSat_content;
    			break;
    		case 'both':
    			$new_content = $spstaSat_content . $content . $spstaSat_content;
    			break;
    		default:
    			$new_content = $content;
    		}
    	}
    }
	return $new_content;
}
/** OLD VERSION END **/

/** SHORTCODES **/
add_shortcode( 'speakstaffPlayer', 'speakstaff_shortcode' );
function speakstaff_shortcode( $atts ){
	$playerId = @isset($atts['playerid']) ? $atts['playerid'] : '';
	$color = get_option( 'spstaSat_buttonColor' );
	$color = @isset($color) ? str_replace('#', '', $color) : '';
    $autoplay = @isset($atts['autoplay']) ? $atts['autoplay'] : '';
    $width = @isset($atts['width']) ? $atts['width'] : '';
    $spstaSat_content = '<div class="speakstaff-player" data-id="' . $playerId . '" data-color="' . $color . '" data-autoplay="' . $autoplay . '" data-width="' . $width . '">' .
						'	<div class="speakstaff-preloader-area">' .
						'		<img src="' . plugins_url('assets', __FILE__) . '/speakstaff-loader.gif" alt="Loading " class="preloader" />' .
						'		<img src="' . plugins_url('assets', __FILE__) . '/speakstaff-loader-logo.png" alt=" SpeakStaff Player..." />' .
						'	</div>' .
						'</div>';
	return $spstaSat_content;
}

/** OLD SHORTCODE **/
add_shortcode( 'speakstaff_player', 'speakstaff_player_shortcode' );
function speakstaff_player_shortcode( $atts ){
	global $post;
	
	$meta = get_post_meta($post->ID ,'_spstaSat_meta', true);
	$spstaSat_content = '';
	if (is_array($meta)) {
    	$spstaSat_content = '<h2>' . __("Artikel vorlesen lassen", "speakstaffSatellit") . '</h2>
    						   <div class="speakstaffplayer">	
    							<div class="speakstaff_related">
    								<a href="http://speakstaff.com" title="' . __("Zu SpeakStaff.com", "speakstaffSatellit") . '" target="_blank">
    									<img class="speakstaff_playericon" src="' . plugins_url('assets', __FILE__) . '/speakstaff_playericon.jpg" />
    								</a>
    								<small>' . __("Scan&Go:", "speakstaffSatellit") . '</small>
									<a href="http://spk.st/ab6go" target="_blank" title="' . __("Scan&Go: Scanne den Audio-Beitrag mit einem QR-Scanner und nimm ihn unterwegs zum Hören mit! Mehr Infos...", "speakstaffSatellit") . '"><img class="ss_qrcode" src="http://chart.apis.google.com/chart?chs=70x70&amp;cht=qr&amp;chld=M&amp;chl=' . $meta['ss_shortlink'] . '"></a>
        							<a target="_blank" href="http://speakstaff.com/user/' . $meta['sprecherURL'] . '">
        								<img class="speakstaff_narrator_img" src="http://speakstaff.com/wp-content/uploads/ultimatemember/' . $meta['sprecherId'] . '/profile_photo-190.jpg" />
    								</a>
    							</div>
    							<iframe class="ss_sc_player" width="' . $meta['width'] . '%" height="166" scrolling="no" frameborder="no" 
    								src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/' . $meta['trackId'] . '&amp;color=' . $meta['color'] . '&amp;auto_play=' . $meta['autoPlay'] . '&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false">
    							</iframe>
        					</div><hr />';	
    }
	return $spstaSat_content;
}
/** END OLD SHORTCODE **/


add_action("admin_print_footer_scripts", "speakstaff_player_shortcode_button_script");
function speakstaff_player_shortcode_button_script() {
    if(wp_script_is("quicktags")) {
        ?>
            <script type="text/javascript">
                QTags.addButton(
                    "speakstaffPlayer",
                    "SpeakStaff Player",
                    callback
                );

                function callback() {
                    QTags.insertContent('[speakstaffPlayer playerid="" width="90" autoplay="false"]');
                }
            </script>
        <?php
    }
}

add_filter("mce_external_plugins", "spstaSat_bindmce_button");
function spstaSat_bindmce_button($plugin_array)
{
    //enqueue TinyMCE plugin script with its ID.
    $plugin_array["speakstaff_player_mce"] =  plugin_dir_url(__FILE__) . "js/spataSat_mce_button.js";
    return $plugin_array;
}

add_filter("mce_buttons", "spstaSat_register_buttons_editor");
function spstaSat_register_buttons_editor($buttons)
{
    //register buttons with their id.
    array_push($buttons, "spstaSat_button");
    return $buttons;
}

/** SETTING PAGE - ADMIN MENU **/
add_action( 'admin_menu', 'spstaSat_menu' );

function spstaSat_menu() {
	add_options_page( 'Speakstaff title', 'Speakstaff', 'manage_options', 'speakstaff-player/pages/admin_menu.php', '', '', 76 );
	
	//call register settings function
	add_action( 'admin_init', 'spstaSat_setPluginSettings' );
	
}

function spstaSat_setPluginSettings() {
	register_setting( 'spstaSat_setPluginSettings', 'spstaSat_buttonColor' );
}


?>