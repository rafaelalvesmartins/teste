<?php
/**
 * Theme styles configuration.
 *
 * @package HiveTheme\Configs
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	[
		'selector'   => '
			.post__image .post__date::before,
			.post--archive .post__footer::before,
			.post-navbar__start .post-navbar__link::before,
			.post-navbar__end .post-navbar__link::before,
			.hp-form--primary::before,
			.hp-listing--view-page .hp-listing__title::before,
			.hp-listing--view-block .hp-listing__attributes--primary::before,
			.hp-listing--view-page .hp-listing__attributes--primary::before,
			.hp-listing-category__link::before,
			.hp-vendor--view-block .hp-vendor__footer::before,
			.hp-vendor--view-page .hp-vendor__attributes--primary::before,
			.hp-offer__attributes--primary .hp-offer__attribute:first-child::before,
			.wp-block-button.is-style-primary .wp-block-button__link
		',

		'properties' => [
			[
				'name'      => 'background-color',
				'theme_mod' => 'primary_color',
			],
		],
	],

	[
		'selector'   => '
			.content-title::before,
			.post__readmore i,
			.post-navbar__link i,
			.pagination > span:not(.dots),
			.pagination .nav-links > span:not(.dots),
			.pagination ul li span.current:not(.dots),
			.hp-page__title::before,
			.hp-section__title::before,
			.hp-listing--view-page .hp-listing__images-carousel .slick-arrow:hover,
			.hp-listing--view-block .hp-listing__details--primary a:hover,
			.hp-listing--view-page .hp-listing__details--primary a:hover,
			.hp-listing--view-block .hp-listing__attributes--primary .hp-listing__attribute,
			.hp-listing--view-page .hp-listing__attributes--primary .hp-listing__attribute,
			.hp-listing-category__link i,
			.hp-vendor--view-block .hp-vendor__attributes--primary .hp-vendor__attribute,
			.hp-vendor--view-page .hp-vendor__attributes--primary .hp-vendor__attribute,
			.hp-vendors--slider .slick-arrow:hover,
			.hp-offer__attributes--primary .hp-offer__attribute,
			.hp-testimonials--slider .slick-arrow:hover,
			.woocommerce nav.woocommerce-pagination > span:not(.dots),
			.woocommerce nav.woocommerce-pagination .nav-links > span:not(.dots),
			.woocommerce nav.woocommerce-pagination ul li span.current:not(.dots)
		',

		'properties' => [
			[
				'name'      => 'color',
				'theme_mod' => 'primary_color',
			],
		],
	],

	[
		'selector'   => '
			.hp-listing--view-page .hp-listing__images-carousel .slick-current img
		',

		'properties' => [
			[
				'name'      => 'border-color',
				'theme_mod' => 'primary_color',
			],
		],
	],

	[
		'selector'   => '
			.hp-listing-category__icon::before,
			.hp-feature__icon,
			.post__categories a::before,
			.post__categories a:hover,
			.tagcloud a::before,
			.wp-block-tag-cloud a::before,
			.tagcloud a:hover,
			.wp-block-tag-cloud a:hover,
			.hp-listing-category__item-count::before
		',

		'properties' => [
			[
				'name'      => 'background-color',
				'theme_mod' => 'secondary_color',
			],
		],
	],

	[
		'selector'   => '
			.hp-listing-category__icon,
			.post__categories a,
			.tagcloud a,
			.wp-block-tag-cloud a,
			.hp-listing-category__item-count
		',

		'properties' => [
			[
				'name'      => 'color',
				'theme_mod' => 'secondary_color',
			],
		],
	],

	[
		'selector'   => '
			.site-header,
			.content-block
		',

		'properties' => [
			[
				'name'      => 'background-color',
				'theme_mod' => 'header_background_color',
			],
		],
	],
];
