<?php
/**
 * Template component.
 *
 * @package HivePress\Components
 */

namespace HivePress\Components;

use HivePress\Helpers as hp;
use HivePress\Blocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Template component class.
 *
 * @class Template
 */
final class Template extends Component {

	/**
	 * Class constructor.
	 *
	 * @param array $args Component arguments.
	 */
	public function __construct( $args = [] ) {
		if ( ! is_admin() ) {

			// Add theme class.
			add_filter( 'body_class', [ $this, 'add_theme_class' ] );

			// Render site header.
			add_action( 'storefront_header', [ $this, 'render_site_header' ], 31 );

			// @deprecated since version 1.3.0.
			add_action( 'hivetheme/v1/render/site_header', [ $this, 'render_site_header' ] );

			// Render site footer.
			add_action( 'wp_footer', [ $this, 'render_site_footer' ] );

			// Remove theme header.
			add_filter( 'twentynineteen_can_show_post_thumbnail', [ $this, 'remove_theme_header' ] );
		}

		parent::__construct( $args );
	}

	/**
	 * Adds theme class.
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public function add_theme_class( $classes ) {

		// Add theme class.
		$classes[] = 'hp-theme--' . hp\sanitize_slug( get_template() );

		// Add template class.
		$route = hivepress()->router->get_current_route_name();

		if ( $route ) {
			$classes[] = 'hp-template--' . hp\sanitize_slug( $route );
		}

		return $classes;
	}

	/**
	 * Renders site header.
	 */
	public function render_site_header() {
		echo ( new Blocks\Template( [ 'template' => 'site_header_block' ] ) )->render();
	}

	/**
	 * Renders site footer.
	 */
	public function render_site_footer() {
		echo ( new Blocks\Template( [ 'template' => 'site_footer_block' ] ) )->render();
	}

	/**
	 * Removes theme header.
	 *
	 * @param bool $display Display flag.
	 * @return bool
	 */
	public function remove_theme_header( $display ) {
		if ( is_singular( hp\prefix( array_keys( hivepress()->get_config( 'post_types' ) ) ) ) ) {
			$display = false;
		}

		return $display;
	}
}
