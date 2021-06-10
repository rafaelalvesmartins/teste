<?php
/**
 * Post types configuration.
 *
 * @package HivePress\Configs
 */

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	'request' => [
		'public'      => true,
		'has_archive' => true,
		'supports'    => [ 'title', 'editor', 'author' ],
		'menu_icon'   => 'dashicons-format-chat',
		'rewrite'     => [ 'slug' => 'request' ],

		'labels'      => [
			'name'               => esc_html__( 'Requests', 'hivepress-requests' ),
			'singular_name'      => esc_html__( 'Request', 'hivepress-requests' ),
			'add_new_item'       => esc_html__( 'Add Request', 'hivepress-requests' ),
			'add_new'            => esc_html_x( 'Add New', 'request', 'hivepress-requests' ),
			'edit_item'          => esc_html__( 'Edit Request', 'hivepress-requests' ),
			'new_item'           => esc_html__( 'Add Request', 'hivepress-requests' ),
			'all_items'          => esc_html__( 'Requests', 'hivepress-requests' ),
			'search_items'       => esc_html__( 'Search Requests', 'hivepress-requests' ),
			'not_found'          => esc_html__( 'No requests found.', 'hivepress-requests' ),
			'not_found_in_trash' => esc_html__( 'No requests found.', 'hivepress-requests' ),
		],
	],
];
