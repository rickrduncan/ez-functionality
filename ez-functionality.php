<?php
/**
 *  Plugin Name: 	EZ Functionality
 *  Plugin URI: 	http://rickrduncan.com/ez-functionality
 *  Donate link:    https://paypal.me/rickrduncan
 *  Description: 	WordPress functionality plugin for theme independent customizations.
 *  Author: 		Rick R. Duncan - B3Marketing, LLC
 *  Author URI: 	http://rickrduncan.com
 *
 * 	Version: 		1.0.0
 *
 *  License: 		GPLv2 or later
 *  License URI: 	http://www.gnu.org/licenses/gpl-2.0.html
 */
 
 
/**
 * Exit if accessed directly.
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;
 

/**
 * Remove 'Editor' from 'Appearance' Menu. 
 * This stops users from being able to edit files from within WordPress admin panel. 
 *
 * @since 1.0.0
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}


/**
 * Add the ability to use shortcodes in widgets
 *
 * @since 1.0.0
 */
add_filter( 'widget_text', 'do_shortcode' ); 


/**
 * Prevent WordPress from compressing images
 *
 * @since 1.0.0
 */
add_filter( 'jpeg_quality', create_function( '', 'return 100;' ) );


/**
 * Limit the number of post revisions to keep
 *
 * @since 1.0.0
 */
add_filter( 'wp_revisions_to_keep', 'ezf_set_revision_max', 10, 2 );
function ezf_set_revision_max( $num, $post ) {     
    $num = 5; //change 5 to match your preferred number of revisions
	return $num; 
}


/**
 * Remove items from the <head> section.
 *
 * @since 1.0.0
 */
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );	//* Remove previous/next post links
remove_action( 'wp_head', 'feed_links', 2 );							//* Remove application/rss+xml feed links
remove_action( 'wp_head', 'feed_links_extra', 3 );						//* Remove application/rss+xml comment feed links
remove_action( 'wp_head', 'wp_generator' );								//* Remove WP Version number
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );				//* Remove shortlink


/**
 * Disable XML RPC.
 *
 * @since 1.0.0
 */
add_filter( 'xmlrpc_enabled', '__return_false' );
remove_action( 'wp_head', 'rsd_link' );				//* Remove rsd_link
remove_action( 'wp_head', 'wlwmanifest_link' );		//* Remove wlwmanifest_link


/**
 * Remove clutter from main dasboard screen.
 *
 * @since 1.0.0
 */
add_action( 'admin_init', 'ezf_remove_dashboard_widgets' ); 
function ezf_remove_dashboard_widgets() {
	//remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); 		// right now
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); 	// recent comments
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal'); 	// incoming links
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal'); 			// plugins
    remove_meta_box('dashboard_quick_press', 'dashboard', 'normal'); 		// quick press
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'normal'); 		// recent drafts
    remove_meta_box('dashboard_primary', 'dashboard', 'normal'); 			// wordpress blog
    remove_meta_box('dashboard_secondary', 'dashboard', 'normal'); 			// other wordpress news
}


/**
 * Remove unwanted core widgets.
 *
 * @since 1.0.0
 */
add_action( 'widgets_init', 'ezf_remove_default_widgets', 11 ); 
function ezf_remove_default_widgets() {
	//unregister_widget('WP_Widget_Pages');
	unregister_widget('WP_Widget_Calendar');
	unregister_widget('WP_Widget_Archives');
	unregister_widget('WP_Widget_Links');
	unregister_widget('WP_Widget_Media_Audio');
	unregister_widget('WP_Widget_Media_Image');
	unregister_widget('WP_Widget_Media_Video');
	unregister_widget('WP_Widget_Meta');
	//unregister_widget('WP_Widget_Search');
    //unregister_widget('WP_Widget_Text');
    //unregister_widget('WP_Widget_Categories');
    //unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    //unregister_widget('WP_Widget_Tag_Cloud');
    //unregister_widget('WP_Nav_Menu_Widget');
    //unregister_widget('WP_Widget_Custom_HTML');
    unregister_widget('Twenty_Eleven_Ephemera_Widget');
}


/**
 * Add superscript and subscript to Tiny MCE editor.
 *
 * @since 1.0.0
 */
add_filter('mce_buttons_2', 'ezf_add_mce_buttons_2'); 
function ezf_add_mce_buttons_2($buttons) {
	$buttons[] = 'superscript';
	$buttons[] = 'subscript';
	return $buttons;
}

 

/**
 *
 * Disable the emoji's.
 *
 * Author: 		Ryan Hellyer
 * Author URI: 	https://geek.hellyer.kiwi/
 *
 * @since 1.0.0
 *
 */ 
add_action( 'init', 'ezf_disable_emojis' );
function ezf_disable_emojis() {
	
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );	
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	
	add_filter( 'tiny_mce_plugins', 'ezf_disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'ezf_disable_emojis_remove_dns_prefetch', 10, 2 );
}


/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * Author: 		Ryan Hellyer
 * Author URI: 	https://geek.hellyer.kiwi/ 
 * 
 * @param    array  $plugins  
 * @return   array  Difference betwen the two arrays
 */
function ezf_disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * Author: 		Ryan Hellyer
 * Author URI: 	https://geek.hellyer.kiwi/ 
 *
 * @param  array  $urls          URLs to print for resource hints.
 * @param  string $relation_type The relation type the URLs are printed for.
 * @return array                 Difference betwen the two arrays.
 */
function ezf_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {

	if ( 'dns-prefetch' == $relation_type ) {

		// Strip out any URLs referencing the WordPress.org emoji location
		$emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
		foreach ( $urls as $key => $url ) {
			if ( strpos( $url, $emoji_svg_url_bit ) !== false ) {
				unset( $urls[$key] );
			}
		}

	}

	return $urls;
}