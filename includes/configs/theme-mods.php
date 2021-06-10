<?php
/**
 * Theme mods configuration.
 *
 * @package HiveTheme\Configs
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	'colors'            => [
		'fields' => [
			'primary_color'           => [
				'default' => '#00876F',
			],

			'secondary_color'         => [
				'default' => '#00876F',
			],

			'header_background_color' => [
				'label'   => esc_html__( 'Header Background Color', 'experthive' ),
				'type'    => 'color',
				'default' => '#FFF1E5',
			],
		],
	],

	'fonts'             => [
		'fields' => [
			'heading_font'        => [
				'default' => 'DM Sans',
			],

			'heading_font_weight' => [
				'default' => '700',
			],

			'body_font'           => [
				'default' => 'DM Sans',
			],

			'body_font_weight'    => [
				'default' => '400,500',
			],
		],
	],

	'static_front_page' => [
		'fields' => [
			'header_images' => [
				'label'   => esc_html__( 'Display header images', 'experthive' ),
				'type'    => 'checkbox',
				'default' => true,
			],
		],
	],
];
