<?php

/*
Plugin Name: Optio Dentistry
Plugin URI: http://www.optiopublishing.com
Description: Add Optio Dentistry patient education videos to your site.
Version: 1.2
Author: Optio Publishing Inc.
Author URI: http://www.optiopublishing.com
*/

define('OPTIO_SERVER_URL', 'https://www.optiopublishing.com');

/**
 * Define shortcodes
 */

add_shortcode( 'optio-library', 'optio_dentistry_video_library' );
add_shortcode( 'optio-video', 'optio_dentistry_video_player' );
add_shortcode( 'optio-thumbnail', 'optio_dentistry_thumbnail_link' );
add_shortcode( 'optio-lightbox', 'optio_dentistry_lightbox' );

/**
 * Embed video library
 */

function optio_dentistry_video_library( $attributes ) {
	$attributes = shortcode_atts(
		array(
			'id' => null,
			'filter' => null
		),
		$attributes
	);
	return optio_dentistry_render_control( 'video_library', $attributes['id'], $attributes['filter'] );
}

/**
 * Embed stand-alone video player
 */

function optio_dentistry_video_player( $attributes ) {
	$attributes = shortcode_atts(
		array(
			'id' => null
		),
		$attributes
	);
	return optio_dentistry_render_control( 'video_player', $attributes['id'] );
}

/**
 * Embed thumbnail link to video in a lightbox
 */

function optio_dentistry_thumbnail_link( $attributes ) {
	$attributes = shortcode_atts(
		array(
			'id' => null
		),
		$attributes
	);
	return optio_dentistry_render_control( 'thumbnail_link', $attributes['id'] );
}

/**
 * Create link to video in a lightbox
 */

function optio_dentistry_lightbox( $attributes, $content ) {
	wp_enqueue_script( 'optio-api', OPTIO_SERVER_URL . '/api/js?v=3', false, '3', true );
	$attributes = shortcode_atts(
		array(
			'id' => null
		),
		$attributes
	);
	return "<a href=\"javascript:optio.openLightbox('{$attributes['id']}');\">$content</a>";
}

/**
 * Render control
 */

function optio_dentistry_render_control( $control = 'video_library', $video_id = null, $filter = null ) {

	// Attempt to render server side
	if ($html = @file_get_contents(
		OPTIO_SERVER_URL . '/embed/?format=html' .
		'&v=3' .
		'&url=' . rawurlencode( $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ) .
		'&q=' . rawurlencode( $_SERVER['QUERY_STRING'] ) .
		'&video=' . rawurldecode( $video_id ) .
		'&filter=' . rawurlencode( $filter ) .
		'&control=' . $control
	)) {
		return $html;
	}

	// Render client side
	return '<script type="text/javascript" src="' . OPTIO_SERVER_URL . '/embed/' .
		'?v=3' .
		'&url=' . rawurlencode( $_SERVER['HTTP_HOST'] ) .
		'&video=' . rawurldecode( $video_id ) .
		'&filter=' . rawurlencode( $filter ) .
		'&control=' . $control . '"></script>';
}

/**
 * Add TinyMCE buttons
 */

function optio_dentistry_buttons() {
	add_filter( 'mce_external_plugins', 'optio_dentistry_add_buttons' );
	add_filter( 'mce_buttons', 'optio_dentistry_register_buttons' );
}

function optio_dentistry_add_buttons( $plugins ) {
	wp_enqueue_style( 'optio-mce', plugins_url( 'css/mce-plugin.css', __FILE__ ) );
	$plugins['optio_dentistry'] = plugin_dir_url( __FILE__ ) . 'js/mce-plugin.js';
	return $plugins;
}

function optio_dentistry_register_buttons( $buttons ) {
	array_push( $buttons, 'optio-library', 'optio-video', 'optio-lightbox' );
	return $buttons;
}

add_action( 'admin_init', 'optio_dentistry_buttons' );

/**
 * TinyMCE dialogs
 */

function optio_dentistry_dialog( $dialog_id = 'insert-video' ) {
	wp_enqueue_script( 'optio-dialog', plugins_url( 'js/dialog.js', __FILE__ ), array( 'jquery' ), false, true );
	wp_enqueue_style( 'optio-dialog', plugins_url( 'css/dialog.css', __FILE__ ) );
	echo
		'<html>
			<head>
				<title>Select a video</title>';
				wp_print_head_scripts();
				wp_print_styles();
	echo
			'</head>
			<body>
				<section>
					<input id="selected" type="hidden">
					<input id="selected-title" type="hidden">
					<header></header>
					<div id="videos"></div>
					<footer>
						<a id="close" href="#">Cancel</a>';
						if ( $dialog_id == 'insert-video' ) echo '<button id="insert" disabled="disabled">Insert</button>';
						if ( $dialog_id == 'insert-link' ) echo '<button id="link" disabled="disabled">Link</button>';
	echo
					'</footer>
				</section>';
				wp_print_footer_scripts();
	echo
			'</body>
		</html>';
	exit();
}

function optio_dentistry_insert_video_dialog() {
	optio_dentistry_dialog( 'insert-video' );
}

function optio_dentistry_insert_link_dialog() {
	optio_dentistry_dialog( 'insert-link' );
}

add_action( 'wp_ajax_optio_insert_video_dialog', 'optio_dentistry_insert_video_dialog' );
add_action( 'wp_ajax_optio_insert_link_dialog', 'optio_dentistry_insert_link_dialog' );

?>
