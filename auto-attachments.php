<?php
/*
Plugin Name: Auto Attachments
Plugin URI: http://www.kaisercrazy.com/cms-sistemleri/wordpress/auto-attachments-0-7.html
Description: This plugin makes your attachments more effective. Supported attachment types are Word, Excel, Pdf, PowerPoint, zip, rar, tar, tar.gz, mp3, flv, mp4 
Version: 0.7
Author: Serkan Algur
Author URI: http://www.kaisercrazy.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
// Stop direct call
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
				die('You are not allowed to call this page directly.');
}
function multilingual_aa( ) {
				// Internationalization, first(!)
				load_plugin_textdomain('autoa', false, dirname(plugin_basename(__FILE__)) . '/languages');
				// Other init stuff, be sure to it after load_plugins_textdomain if it involves translated text(!)
}
add_action('init', 'multilingual_aa');

//Call Metabox
include 'admin/metaboxes.php';
//Call Shortcode
include 'admin/shortcodes.php';
//Call Rebuild
include 'admin/rebuild.php';

//ACTIVATE (MULTISITES)
register_activation_hook(__FILE__, 'aa_install');
function aa_install( ) {
				global $wpdb;
				if (function_exists('is_multisite') && is_multisite()) {
								// check if it is a network activation - if so, run the activation function for each blog id
								if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) {
												$old_blog = $wpdb->blogid;
												// Get all blog ids
												$blogids  = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
												foreach ($blogids as $blog_id) {
																switch_to_blog($blog_id);
																_aa_install();
												}
												switch_to_blog($old_blog);
												return;
								}
				}
				_aa_install();
}
function _aa_install() {
			$aaopt = array (
				'mp3_listen' 	=> 'Files to Listen',
				'video_watch' 	=> 'Files to Watch',
				'before_title'	=> 'Here is the attachments of this Post',
				'show_b_title' 	=> 'yes',
				'showmp3info'	=> 'yes',
				'showvideoinfo'	=> 'yes',
				'galeri' 		=> 'yes',
				'thw'			=> '100',
				'thh'			=> '100',
				'tbhw' 			=> '800',
				'tbhh'			=> '600',
				'fhw' 			=> '48',
				'fhh' 			=> '48',
				'jhw' 			=> '470',
				'jhh' 			=> '325',
				'page_ok' 		=> 'no',
				'category_ok'	=> 'no',
				'use_colorbox' 	=> 'no',
				'homepage_ok' 	=> 'no',
				'listview' 		=> 'no',
				'newwindow' 	=> 'no',
				'jwskin' 		=> '',
				'slimstyle' 	=> 'light',
				'galstyle' 		=> 'light'
				);
				
				// if old options exist, update to new system
				foreach( $aaopt as $key => $value ) {
					if( $existing = get_option($key) ) {
					$aaopt[$key] = $existing;
					delete_option($key);
					}
				}
			add_option('auto_attachments_options', $aaopt);
}
//DeACTIVATE (MULTISITES)
register_deactivation_hook(__FILE__, 'aa_uninstall');
function aa_uninstall( ) {
				global $wpdb;
				if (function_exists('is_multisite') && is_multisite()) {
								// check if it is a network activation - if so, run the activation function for each blog id
								if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) {
												$old_blog = $wpdb->blogid;
												// Get all blog ids
												$blogids  = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
												foreach ($blogids as $blog_id) {
																switch_to_blog($blog_id);
																_aa_uninstall();
												}
												switch_to_blog($old_blog);
												return;
								}
				}
				_aa_uninstall();
}
function _aa_uninstall( ) {	
				delete_option('auto_attachments_options');
}
//Admin Area Accordion 
function admin_aa_scripts( ) {
				$urlp = plugins_url('/auto-attachments/includes');
				wp_register_script('auto-attachments1', '' . $urlp . '/js/ui.ms.js', __FILE__);
				wp_register_script('auto-attachments2', '' . $urlp . '/js/aa.js', __FILE__);

				wp_enqueue_script('auto-attachments1');
				wp_enqueue_script('auto-attachments2');
}
function admin_aa_styles( ) {
				$urlp = plugins_url('/auto-attachments/includes');
				wp_enqueue_style('customcss', '' . $urlp . '/js/css/custom/ui.css');
}
//Admin Area Accordion
//Add Css into Header (Header Text Options (added with v0.2.6))
add_action('wp_head', 'addHeaderCode');
function addHeaderCode( ) {
				$opts = get_option('auto_attachments_options');
				$urlp = plugins_url('/auto-attachments');
				echo '<link type="text/css" rel="stylesheet" href="' . $urlp . '/a-a.css" />' . "\n";
				echo '<script language="javascript" type="text/javascript" src="' . $urlp . '/includes/jw/swfobject.js"></script>'."\n";
				//With 0.2.6 you can decide show or hide :)
				if ($opts['showmp3info'] == 'no') {
								echo '<style>div.mp3info {display:none;}</style>';
				}
				if ($opts['showvideoinfo'] == 'no') {
								echo '<style>div.videoinfo {display:none;}</style>';
				}
}
$opts = get_option('auto_attachments_options');
//Colorbox usage (added with 0.2.7)
if ($opts['use_colorbox'] == 'yes') {
				add_action('wp_print_scripts', 'enqueue_aa_scripts');
				add_action('wp_print_styles', 'enqueue_aa_styles');
				function enqueue_aa_scripts( ) {
								$urlp = plugins_url('/auto-attachments/includes');
								wp_enqueue_script('jquery');
								wp_enqueue_script('tinybox_script', '' . $urlp . '/js/slimbox2.js', array(
												'jquery'
								));
				}
				function enqueue_aa_styles()
					{
							$opts = get_option('auto_attachments_options');
							$urlp = plugins_url('/auto-attachments/includes');
							if ($opts['slimstyle'] == 'dark' ){
							wp_enqueue_style('slimbox_css_dark', '' . $urlp . '/js/slimbox/slimbox-dark.css');
							} else {
							wp_enqueue_style('slimbox_css', '' . $urlp . '/js/slimbox/slimbox.css');
							}
							
					}
}
//Admin Area
//Custom Admin Area Settinngs
add_action('admin_menu', 'aa_admin_page');
function aa_admin_page( ) {
				$page = add_menu_page(__('Auto Attachments', 'autoa'), __('Auto Attachments', 'autoa'), '10', 'auto_attachments', 'aa_settings', plugins_url('auto-attachments/includes/images/aamenu.png'));
				add_action('admin_print_scripts-'.$page , 'admin_aa_scripts');
				add_action('admin_print_styles', 'admin_aa_styles');
}


function aa_settings( ) {
				global $_POST, $wpdb;
				//Update Option (Changed with 0.5 [Multisite Supp.])
				if ($_POST['serkoup'] == 'uppo') {
					//Form data sent
					$a_new = $_POST['autoa'];
					$a_old = get_option('auto_attachments_options');
					$check_opt = array ('mp3_listen','video_watch','before_title','show_b_title','showmp3info','showvideoinfo','galeri','thw','thh','tbhw','tbhh','fhw','fhh','jhw','jhh','page_ok','category_ok','use_colorbox','homepage_ok','listview','newwindow','jwskin','slimstyle','galstyle');
					foreach ($check_opt as $aa) {
						$a_old[$aa] = $a_new[$aa] ? $a_old[$aa] : $a_new[$aa];
						}
							update_option( 'auto_attachments_options', $a_new);
							echo '<div id="message" class="updated fade"><p><strong>' . __('Settings saved.') . '</strong></p></div>';
					}
				//Start to write admin area
				include 'admin/admin-area.php'; //I included because HTML Codes too Mainstream :)
				//Admin area finish
				
}
$opts = get_option('auto_attachments_options');
add_image_size('aa_big', $opts['tbhw'], $opts['tbhh']);
add_image_size('aa_thumb', $opts['thw'], $opts['thh'], TRUE);


// Function Area 
function get_attachment_icons( ) {
				$opts 			   = get_option('auto_attachments_options');
				$urlp              = plugins_url('/auto-attachments/includes');
				$before_title_text = $opts['before_title'];
				$b_title           = $opts['show_b_title'];
				$aa_string         = "<div class='dIW2'>";
				if ($b_title == 'yes') {
								$aa_string .= "$before_title_text<br />";
				} else {
				}
				if ($opts['listview'] == 'yes') {
								$aa_string .= "<ul>";
				}
				$ex_dosya = get_post_meta(get_the_ID(), 'ex_dosya', TRUE);
				if ($files = get_children(array( //do only if there are attachments of these qualifications
								'post_parent' => get_the_ID(),
								'post_type' => 'attachment',
								'numberposts' => -1,
								'post_mime_type' => array(
												"application/pdf",
												"application/rar",
												"application/msword",
												"application/vnd.ms-powerpoint",
												"application/vnd.ms-excel",
												"application/zip",
												"application/x-rar-compressed",
												"application/x-tar",
												"application/x-gzip",
												"application/vnd.oasis.opendocument.spreadsheet",
												"application/vnd.oasis.opendocument.formula",
												"text/plain",
												"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
												"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
												"application/vnd.openxmlformats-officedocument.presentationml.presentation",
												"application/x-compress",
												"application/mathcad",
												"application/postscript"
								),
								'exclude' => $ex_dosya
								//MIME Type condition (changed into this format with 0.4.1)
				))) {
								foreach ($files as $file) //setup array for more than one file attachment
												{
												$fhh = $opts['fhh'];
												$fhw = $opts['fhw'];
												if ($opts['newwindow'] == 'yes') {
																$target = 'target="_blank"';
												} else {
																$target = "";
												}
												$file_link       = wp_get_attachment_url($file->ID); //get the url for linkage
												$file_name_array = explode("/", $file_link);
												$file_post_mime  = str_replace("/", "-", $file->post_mime_type);
												$file_name       = array_reverse($file_name_array); //creates an array out of the url and grabs the filename
												if ($opts['listview'] == 'yes') {
																$aa_string .= "<li id='$file->ID'>";
																$aa_string .= "<a style='font-weight:bold;text-decoration:none;' href='$file_link' $target><span class='ikon kaydet'></span>" . $file->post_title . "</a> ";
																$aa_string .= "</li>";
												} else {
																$aa_string .= "<div class='dI' id='$file->ID'>";
																$aa_string .= "<a href='$file_link' $target>";
																$aa_string .= "<img src='$urlp/images/mime/" . $file_post_mime . ".png' width='$fhw' height='$fhh'/>";
																$aa_string .= "</a>";
																$aa_string .= "<a class='dItitle' href='$file_link'>" . $file->post_title . "</a>";
																$aa_string .= "</div>";
												}
								}
				}
				if ($opts['listview'] == 'yes') {
								$aa_string .= "</ul>";
				}
				$aa_string .= "</div><div style='clear:both;'></div>";
				//Audio Files
				$ex_muz = get_post_meta(get_the_ID(), 'ex_muz', TRUE);
				$mp3s = get_children(array( //do only if there are attachments of these qualifications
								'post_parent' => get_the_ID(),
								'post_type' => 'attachment',
								'numberposts' => -1,
								'post_mime_type' => 'audio', //MIME Type condition
								'exclude' => $ex_muz
				));
				if (!empty($mp3s)):
								$skin = $opts['jwskin'];
								$jhw  = $opts['jhw'];
								$aa_string .= "<div class='dIW'><div class='mp3info'>" . $opts['mp3_listen'] . "</div><ul>";
								foreach ($mp3s as $mp3):
												$aa_string .= "<li>";
												if (!empty($mp3->post_title)): //checking to make sure the post title isn't empty
												endif;
												if (!empty($mp3->post_content)): //checking to make sure something exists in post_content (description)
												endif;
												$aa_string .= "<div id='mediaspace" . $mp3->ID . "'></div>";
												$aa_string .= "<script type='text/javascript'>";
												$aa_string .= "var so = new SWFObject('$urlp/jw/player.swf','ply','$jhw','24','9','#000000');";
												$aa_string .= "so.addParam('allowfullscreen','false');";
												$aa_string .= "so.addParam('allowscriptaccess','always');";
												$aa_string .= "so.addParam('wmode','opaque');";
												$aa_string .= "so.addVariable('file','" . $mp3->guid . "');";
												$aa_string .= "so.addVariable('skin','" . $urlp . "/jw/skins/" . $skin . ".zip');";
												$aa_string .= "so.write('mediaspace" . $mp3->ID . "');";
												$aa_string .= "</script>";
												$aa_string .= "<span class='mp3title'>" . $mp3->post_title . " - " . $mp3->post_content . "</span>";
												$aa_string .= "</li>";
								endforeach;
								$aa_string .= "</ul></div>";
				endif;
				//Video Support flv, mp4, etc. added with 0.2
				$ex_vid = get_post_meta(get_the_ID(), 'ex_vid', TRUE);
				$videoss = get_children(array( //do only if there are attachments of these qualifications
								'post_parent' => get_the_ID(),
								'post_type' => 'attachment',
								'numberposts' => -1,
								'post_mime_type' => 'video', //MIME Type condition
								'exclude' => $ex_vid
				));
				if (!empty($videoss)):
								$jhw  = $opts['jhw'];
								$jhh  = $opts['jhh'];
								$aa_string .= "<div class='dIW'><div class='videoinfo'>" . $opts['video_watch'] . "</div><ul>";
								foreach ($videoss as $videos):
												$aa_string .= "<li>";
												if (!empty($videos->post_title)): //checking to make sure the post title isn't empty
												endif;
												if (!empty($videos->post_content)): //checking to make sure something exists in post_content (description)
												endif;
												$aa_string .= "<div id='mediaspace" . $videos->ID . "'></div>";
												$aa_string .= "<script type='text/javascript'>";
												$aa_string .= "var so = new SWFObject('$urlp/jw/player.swf','ply','$jhw','$jhh','9','#000000');";
												$aa_string .= "so.addParam('allowfullscreen','true');";
												$aa_string .= "so.addParam('allowscriptaccess','always');";
												$aa_string .= "so.addParam('wmode','opaque');";
												$aa_string .= "so.addVariable('file','" . $videos->guid . "');";
												$aa_string .= "so.addVariable('skin','" . $urlp . "/jw/skins/" . $skin . ".zip');";
												$aa_string .= "so.write('mediaspace" . $videos->ID . "');";
												$aa_string .= "</script>";
												$aa_string .= "<span class='mp3title'>" . $videos->post_title . " - " . $videos->post_content . "</span>";
												$aa_string .= "</li>";
								endforeach;
								$aa_string .= "</ul></div>";
				endif;
				if ($opts['galeri'] == 'yes') {
								global $blog_id, $current_site;
								$thumb_ID = get_post_thumbnail_id( get_the_ID());
								$ex_rsm = get_post_meta(get_the_ID(), 'ex_rsm', TRUE);
								if ($galeriresim = get_children(array( //do only if there are attachments of these qualifications
												'post_parent' => get_the_ID(),
												'post_type' => 'attachment',
												'numberposts' => -1,
												'post_mime_type' => 'image', //MIME Type condition
												'exclude' => $thumb_ID.','.$ex_rsm
								))) {
												$aa_string .= "<div class='dIW1'><div class='galeri-".$opts['galstyle']."'>";
												foreach ($galeriresim as $galerir) //setup array for more than one file attachment
																{
																$file_link       = wp_get_attachment_url($galerir->ID); //get the url for linkage
																$file_name_array = explode("/", $galrerir_link);
																$aath            = wp_get_attachment_image_src($galerir->ID, 'aa_thumb');
																$aabg            = wp_get_attachment_image_src($galerir->ID, 'aa_big');
																$aa_string .= "<a href='$aabg[0]' rel='lightbox-grp'>";
																if (isset($blog_id) && $blog_id > 1) //fix for TimThumb
																				{
																				$image_link_parts = explode("/files/", $galerir->guid); //fix for TimThumb
																				$aa_string .= "<img src='$aath[0]'/>";
																				$aa_string .= "</a>";
																} else {
																				$aa_string .= "<img src='$aath[0]'/>";
																				$aa_string .= "</a>";
																}
												}
												$aa_string .= "</div></div>";
								}
				}
				$aa_string .= "<div style='clear:both;'></div>";
				// Last Check for attachments (Needed After "Before Title option") Thanks Kris! :)
				$aargu = get_children(array(
								'post_parent' => get_the_ID(),
								'post_type' => 'attachment',
								'numberposts' => -1
				));
				if (!empty($aargu)):
								return $aa_string;
				endif;
}
//Insert code after the_content (!important) Changed into 3 parts with 0.5 (after this suggestion http://wordpress.org/support/topic/plugin-auto-attachments-does-not-show-attachments-for-posts-on-the-home-page?replies=2#post-2627965 )
add_filter('the_content', 'insertintoContent');
function insertintoContent($content) {
				if (is_single()) {
								$content .= get_attachment_icons();
				}
				return $content;
}
// Home Page Function Corrected with 0.5.2
if ($opts['homepage_ok'] == 'yes') {
				function insertintoHome($content) {
								if (is_home()) {
												$content .= get_attachment_icons();
								}
								return $content;
				}
				add_filter('the_content', 'insertintoHome');
}

if ($opts['category_ok'] == 'yes') {
				function insertintoCategory($content) {
								if (is_category()&&is_archive()) {
												$content .= get_attachment_icons();
								}
								return $content;
				}
				add_filter('the_content', 'insertintoCategory');
}




function insertintoPage($content) {
		if (is_page()) {
				$post_id = get_the_ID();
					$aa_show_page = get_post_meta($post_id, 'aa_page_meta', TRUE);
						if ($aa_show_page['show'] == 'yes'){
							$content .= get_attachment_icons();
							}
						}
			return $content;
		}
add_filter('the_content', 'insertintoPage');

//Show Plugin Version into Admin Page
function plugin_get_version( ) {
				if (!function_exists('get_plugins'))
								require_once(ABSPATH . 'wp-admin/includes/plugin.php');
				$plugin_folder = get_plugins('/' . plugin_basename(dirname(__FILE__)));
				$plugin_file   = basename((__FILE__));
				return $plugin_folder[$plugin_file]['Version'];
}
?>