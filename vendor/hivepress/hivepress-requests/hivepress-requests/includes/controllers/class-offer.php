<?php
/**
 * Offer controller.
 *
 * @package HivePress\Controllers
 */

namespace HivePress\Controllers;

use HivePress\Helpers as hp;
use HivePress\Models;
use HivePress\Forms;
use HivePress\Emails;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Offer controller class.
 *
 * @class Offer
 */
final class Offer extends Controller {

	/**
	 * Class constructor.
	 *
	 * @param array $args Controller arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'routes' => [
					'offers_resource'   => [
						'path' => '/offers',
						'rest' => true,
					],

					'offer_make_action' => [
						'base'   => 'offers_resource',
						'method' => 'POST',
						'action' => [ $this, 'make_offer' ],
						'rest'   => true,
					],

					'offer_accept_page' => [
						'path'     => '/accept-offer/(?P<offer_id>\d+)',
						'redirect' => [ $this, 'redirect_offer_accept_page' ],
					],
				],
			],
			$args
		);

		parent::__construct( $args );
	}

	/**
	 * Makes offer.
	 *
	 * @param WP_REST_Request $request API request.
	 * @return WP_Rest_Response
	 */
	public function make_offer( $request ) {

		// Check authentication.
		if ( ! is_user_logged_in() ) {
			return hp\rest_error( 401 );
		}

		// Validate form.
		$form = ( new Forms\Offer_Make() )->set_values( $request->get_params() );

		if ( ! $form->validate() ) {
			return hp\rest_error( 400, $form->get_errors() );
		}

		// Get bidder.
		$bidder_id = $request->get_param( 'bidder' ) ? $request->get_param( 'bidder' ) : get_current_user_id();

		$bidder = Models\User::query()->get_by_id( $bidder_id );

		if ( ! $bidder ) {
			return hp\rest_error( 400 );
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_users' ) && get_current_user_id() !== $bidder->get_id() ) {
			return hp\rest_error( 403 );
		}

		// Get request.
		$_request = Models\Request::query()->get_by_id( $form->get_value( 'request' ) );

		if ( ! $_request || ! in_array( $_request->get_status(), [ 'private', 'publish' ], true ) || ( $_request->get_status() === 'private' && ! $_request->get_vendor__id() ) ) {
			return hp\rest_error( 400 );
		}

		if ( $_request->get_user__id() === $bidder->get_id() ) {
			return hp\rest_error( 403, esc_html__( 'You can\'t submit offers for your own requests.', 'hivepress-requests' ) );
		}

		// Add offer.
		$offer = ( new Models\Offer() )->fill(
			array_merge(
				$form->get_values(),
				[
					'bidder'               => $bidder->get_id(),
					'bidder__display_name' => $bidder->get_display_name(),
					'bidder__email'        => $bidder->get_email(),
					'approved'             => get_option( 'hp_offer_enable_moderation' ) ? 0 : 1,
				]
			)
		);

		if ( ! $offer->save() ) {
			return hp\rest_error( 400, $offer->_get_errors() );
		}

		// Send email.
		( new Emails\Offer_Submit(
			[
				'recipient' => get_option( 'admin_email' ),

				'tokens'    => [
					'user'      => $bidder,
					'request'   => $_request,
					'offer'     => $offer,
					'offer_url' => admin_url( 'edit-comments.php' ),
				],
			]
		) )->send();

		return hp\rest_response(
			201,
			[
				'id' => $offer->get_id(),
			]
		);
	}

	/**
	 * Redirects offer accept page.
	 *
	 * @return mixed
	 */
	public function redirect_offer_accept_page() {

		// Check Marketplace status.
		if ( ! hivepress()->get_version( 'marketplace' ) ) {
			return true;
		}

		// Get offer.
		$offer = Models\Offer::query()->get_by_id( hivepress()->request->get_param( 'offer_id' ) );

		if ( ! $offer || ! $offer->is_approved() ) {
			return true;
		}

		// Get request.
		$request = $offer->get_request();

		if ( ! $request || ! in_array( $request->get_status(), [ 'private', 'publish' ], true ) || ( $request->get_status() === 'private' && ! $request->get_vendor__id() ) ) {
			return true;
		}

		if ( get_current_user_id() !== $request->get_user__id() ) {
			return true;
		}

		// Update request.
		if ( $offer->get_price() ) {
			$request->set_budget( $offer->get_price() )->save_budget();
		}

		// Get product.
		$product = hp\get_first_array_value(
			wc_get_products(
				[
					'parent' => $request->get_id(),
					'limit'  => 1,
				]
			)
		);

		if ( ! $product || ! in_array( $product->get_status(), [ 'private', 'publish' ], true ) ) {
			return true;
		}

		// Update product.
		wp_update_post(
			[
				'ID'          => $product->get_id(),
				'post_author' => $offer->get_bidder__id(),
			]
		);

		// Add product to cart.
		WC()->cart->empty_cart();
		WC()->cart->add_to_cart( $product->get_id() );

		return wc_get_page_permalink( 'checkout' );
	}
}
