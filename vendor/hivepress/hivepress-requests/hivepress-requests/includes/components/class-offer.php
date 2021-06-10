<?php
/**
 * Offer component.
 *
 * @package HivePress\Components
 */

namespace HivePress\Components;

use HivePress\Helpers as hp;
use HivePress\Emails;
use HivePress\Models;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Offer component class.
 *
 * @class Offer
 */
final class Offer extends Component {

	/**
	 * Class constructor.
	 *
	 * @param array $args Component arguments.
	 */
	public function __construct( $args = [] ) {

		// Check requests.
		add_action( 'hivepress/v1/events/hourly', [ $this, 'check_requests' ] );

		// Add attribute models.
		add_filter( 'hivepress/v1/components/attribute/models', [ $this, 'add_attribute_models' ] );

		// Add vendor fields.
		add_filter( 'hivepress/v1/models/vendor', [ $this, 'add_vendor_fields' ] );

		// Update request status.
		add_action( 'hivepress/v1/models/request/update_status', [ $this, 'update_request_status' ], 10, 4 );

		// Update offer status.
		add_action( 'hivepress/v1/models/offer/create', [ $this, 'update_offer_status' ], 10, 2 );
		add_action( 'hivepress/v1/models/offer/update_status', [ $this, 'update_offer_status' ], 10, 4 );

		// Validate offer.
		add_filter( 'hivepress/v1/models/offer/errors', [ $this, 'validate_offer' ], 10, 2 );

		if ( hivepress()->get_version( 'marketplace' ) ) {

			// Update order status.
			add_action( 'woocommerce_order_status_changed', [ $this, 'update_order_status' ], 10, 4 );

			// Manage requests.
			add_action( 'hivepress/v1/models/request/create', [ $this, 'update_request' ], 10, 2 );
			add_action( 'hivepress/v1/models/request/update', [ $this, 'update_request' ], 10, 2 );
			add_action( 'hivepress/v1/models/request/delete', [ $this, 'delete_request' ] );

			// Add request attributes.
			add_filter( 'hivepress/v1/models/request/attributes', [ $this, 'add_request_attributes' ] );

			// Add offer fields.
			add_filter( 'hivepress/v1/models/offer', [ $this, 'add_offer_fields' ] );
			add_filter( 'hivepress/v1/forms/offer_make', [ $this, 'add_offer_fields' ] );

			if ( ! is_admin() ) {

				// Alter templates.
				add_filter( 'hivepress/v1/templates/offer_view_block/blocks', [ $this, 'alter_offer_view_blocks' ], 10, 2 );
				add_filter( 'hivepress/v1/templates/order_footer_block/blocks', [ $this, 'alter_order_footer_blocks' ], 10, 2 );
			}
		}

		if ( ! is_admin() ) {

			// Set request context.
			add_action( 'init', [ $this, 'set_request_context' ], 100 );

			// Allow request access.
			add_filter( 'user_has_cap', [ $this, 'allow_request_access' ], 10, 3 );

			// Alter menus.
			add_filter( 'hivepress/v1/menus/user_account', [ $this, 'alter_user_account_menu' ] );

			// Alter templates.
			add_filter( 'hivepress/v1/templates/site_header_block', [ $this, 'alter_site_header_block' ] );
			add_filter( 'hivepress/v1/templates/vendor_view_page', [ $this, 'alter_vendor_view_page' ] );
		}

		parent::__construct( $args );
	}

	/**
	 * Gets order request.
	 *
	 * @param WC_Order $order Order object.
	 * @return mixed
	 */
	protected function get_order_request( $order ) {
		$request = null;

		// Get product ID.
		$product_id = hp\get_first_array_value( hivepress()->woocommerce->get_order_product_ids( $order ) );

		if ( $product_id ) {

			// Get request ID.
			$request_id = wp_get_post_parent_id( $product_id );

			if ( $request_id ) {

				// Get request.
				$request = Models\Request::query()->get_by_id( $request_id );
			}
		}

		return $request;
	}

	/**
	 * Checks requests.
	 */
	public function check_requests() {
		if ( get_option( 'hp_request_expiration_period' ) ) {

			// Get expired requests.
			$expired_requests = Models\Request::query()->filter(
				[
					'status__in'        => [ 'private', 'publish' ],
					'expired_time__lte' => time(),
				]
			)->get();

			// Update expired requests.
			foreach ( $expired_requests as $request ) {
				if ( $request->get_status() === 'publish' || $request->get_vendor__id() ) {

					// Delete request.
					$request->trash();

					// Send email.
					$user = $request->get_user();

					if ( $user ) {
						( new Emails\Request_Expire(
							[
								'recipient' => $user->get_email(),

								'tokens'    => [
									'user'          => $user,
									'request'       => $request,
									'user_name'     => $user->get_display_name(),
									'request_title' => $request->get_title(),
								],
							]
						) )->send();
					}
				}
			}
		}

		if ( get_option( 'hp_request_notify_vendors' ) ) {

			// Get vendors.
			$vendors = Models\Vendor::query()->filter(
				[
					'status' => 'publish',
				]
			)->limit( 10 )
			->set_args(
				[
					'orderby'    => 'meta_value_num',
					'order'      => 'ASC',

					'meta_query' => [
						'relation' => 'OR',

						[
							'key'     => 'hp_request_checked_time',
							'type'    => 'NUMERIC',
							'compare' => 'EXISTS',
						],

						[
							'key'     => 'hp_request_checked_time',
							'type'    => 'NUMERIC',
							'compare' => 'NOT EXISTS',
						],
					],
				]
			)->get();

			// Get first request.
			$request = Models\Request::query()->filter(
				[
					'status' => 'publish',
				]
			)->order( [ 'created_date' => 'desc' ] )
			->get_first();

			// Get requests URL.
			$requests_url = hivepress()->router->get_url( 'requests_view_page' );

			foreach ( $vendors as $vendor ) {
				if ( $request ) {

					// Get found time.
					$found_time = strtotime( $request->get_created_date() );

					if ( $found_time > $vendor->get_request_found_time() ) {

						// Set found time.
						$vendor->set_request_found_time( $found_time );

						// Send email.
						$user = $vendor->get_user();

						if ( $user ) {
							( new Emails\Request_Find(
								[
									'recipient' => $user->get_email(),
									'subject'   => esc_html__( 'New Requests', 'hivepress-requests' ),

									'tokens'    => [
										'user'         => $user,
										'user_name'    => $user->get_display_name(),
										'requests_url' => $requests_url,
									],
								]
							) )->send();
						}
					}
				}

				// Update vendor.
				$vendor->fill(
					[
						'request_checked_time' => time(),
					]
				)->save(
					[
						'request_checked_time',
						'request_found_time',
					]
				);
			}
		}
	}

	/**
	 * Adds attribute models.
	 *
	 * @param array $models Model names.
	 * @return array
	 */
	public function add_attribute_models( $models ) {
		$models[] = 'request';

		return $models;
	}

	/**
	 * Adds vendor fields.
	 *
	 * @param array $model Model arguments.
	 * @return array
	 */
	public function add_vendor_fields( $model ) {
		$model['fields']['request_checked_time'] = [
			'type'      => 'number',
			'min_value' => 0,
			'_external' => true,
		];

		$model['fields']['request_found_time'] = [
			'type'      => 'number',
			'min_value' => 0,
			'_external' => true,
		];

		return $model;
	}

	/**
	 * Updates request status.
	 *
	 * @param int    $request_id Request ID.
	 * @param string $new_status New status.
	 * @param string $old_status Old status.
	 * @param object $request Request object.
	 */
	public function update_request_status( $request_id, $new_status, $old_status, $request ) {
		if ( get_option( 'hp_request_enable_moderation' ) && 'pending' === $old_status ) {

			// Get user.
			$user = $request->get_user();

			if ( $user ) {
				if ( 'publish' === $new_status ) {

					// Update request status.
					if ( get_option( 'hp_request_allow_sending' ) && $request->get_vendor__id() ) {
						$request->set_status( 'private' )->save_status();
					}

					// Send approval email.
					( new Emails\Request_Approve(
						[
							'recipient' => $user->get_email(),

							'tokens'    => [
								'user'          => $user,
								'request'       => $request,
								'user_name'     => $user->get_display_name(),
								'request_title' => $request->get_title(),
								'request_url'   => get_permalink( $request->get_id() ),
							],
						]
					) )->send();
				} elseif ( 'trash' === $new_status ) {

					// Send rejection email.
					( new Emails\Request_Reject(
						[
							'recipient' => $user->get_email(),

							'tokens'    => [
								'user'          => $user,
								'request'       => $request,
								'user_name'     => $user->get_display_name(),
								'request_title' => $request->get_title(),
							],
						]
					) )->send();
				}
			}
		}

		if ( in_array( $new_status, [ 'publish', 'private', 'pending' ], true ) ) {

			// Get expiration period.
			$expiration_period = absint( get_option( 'hp_request_expiration_period' ) );

			if ( $expiration_period && ! $request->get_expired_time() ) {

				// Set expiration time.
				$request->set_expired_time( time() + $expiration_period * DAY_IN_SECONDS )->save_expired_time();
			}
		}

		if ( get_option( 'hp_request_allow_sending' ) && 'private' === $new_status && $request->get_vendor__id() ) {

			// Get user.
			$user = $request->get_vendor__user();

			// Send request email.
			if ( $user ) {
				( new Emails\Request_Send(
					[
						'recipient' => $user->get_email(),
						/* translators: %s: user name. */
						'subject'   => sprintf( hp\sanitize_html( __( 'New request from %s', 'hivepress-requests' ) ), $request->get_user__display_name() ),

						'tokens'    => [
							'user'          => $user,
							'request'       => $request,
							'user_name'     => $user->get_display_name(),
							'request_title' => $request->get_title(),
							'request_url'   => get_permalink( $request->get_id() ),
						],
					]
				) )->send();
			}
		}
	}

	/**
	 * Updates offer status.
	 *
	 * @param int    $offer_id Offer ID.
	 * @param string $new_status New status.
	 * @param string $old_status Old status.
	 * @param object $offer Offer object.
	 */
	public function update_offer_status( $offer_id, $new_status, $old_status = null, $offer = null ) {

		// Get offer.
		if ( is_null( $offer ) ) {
			$offer = $new_status;
		}

		// Get moderation flag.
		$moderate = get_option( 'hp_offer_enable_moderation' );

		if ( ( $moderate && 'approve' === $new_status ) || ( ! $moderate && is_object( $new_status ) ) ) {

			// Get request.
			$request = $offer->get_request();

			if ( $request && ( $request->get_status() === 'publish' || ( $request->get_status() === 'private' && $request->get_vendor__id() ) ) ) {

				// Send email.
				$user = $request->get_user();

				if ( $user ) {
					( new Emails\Offer_Make(
						[
							'recipient' => $user->get_email(),

							'tokens'    => [
								'user'      => $user,
								'request'   => $request,
								'offer'     => $offer,
								'user_name' => $user->get_display_name(),
								'offer_url' => get_permalink( $request->get_id() ) . '#offers',
							],
						]
					) )->send();
				}
			}
		}
	}

	/**
	 * Validates offer.
	 *
	 * @param array  $errors Error messages.
	 * @param object $offer Offer object.
	 * @return array
	 */
	public function validate_offer( $errors, $offer ) {
		if ( ! $offer->get_id() && ! $errors ) {

			// Get vendor ID.
			$vendor_id = Models\Vendor::query()->filter(
				[
					'user' => $offer->get_bidder__id(),
				]
			)->get_first_id();

			// Add error.
			if ( ! $vendor_id ) {
				$errors[] = hivepress()->translator->get_string( 'only_vendors_can_make_offers' );
			}

			if ( ! get_option( 'hp_offer_allow_multiple' ) ) {

				// Get offer ID.
				$offer_id = Models\Offer::query()->filter(
					[
						'bidder'  => $offer->get_bidder__id(),
						'request' => $offer->get_request__id(),
					]
				)->get_first_id();

				// Add error.
				if ( $offer_id ) {
					$errors[] = esc_html__( 'You\'ve already submitted an offer.', 'hivepress-requests' );
				}
			}
		}

		return $errors;
	}

	/**
	 * Updates order status.
	 *
	 * @param int      $order_id Order ID.
	 * @param string   $old_status Old status.
	 * @param string   $new_status New status.
	 * @param WC_Order $order Order object.
	 */
	public function update_order_status( $order_id, $old_status, $new_status, $order ) {

		// Check vendor.
		if ( ! $order->get_meta( 'hp_vendor' ) ) {
			return;
		}

		if ( in_array( $new_status, [ 'processing', 'completed', 'refunded' ], true ) ) {

			// Get request.
			$request = $this->get_order_request( $order );

			if ( $request ) {

				// Update request.
				if ( 'processing' === $new_status ) {
					$request->fill(
						[
							'status' => 'private',
							'vendor' => null,
						]
					)->save( [ 'status', 'vendor' ] );
				} else {
					$request->trash();
				}
			}
		}
	}

	/**
	 * Updates request.
	 *
	 * @param int    $request_id Request ID.
	 * @param object $request Request object.
	 */
	public function update_request( $request_id, $request ) {

		// Get product.
		$product = hp\get_first_array_value(
			wc_get_products(
				[
					'parent' => $request->get_id(),
					'limit'  => 1,
				]
			)
		);

		if ( ! $product ) {
			if ( $request->get_status() === 'publish' || ( $request->get_status() === 'private' && $request->get_vendor__id() ) ) {

				// Add product.
				$product = new \WC_Product();

				// Set properties.
				$product->set_props(
					[
						'parent_id'          => $request->get_id(),
						'catalog_visibility' => 'hidden',
						'virtual'            => true,
					]
				);
			} else {
				return;
			}
		}

		// Set properties.
		$product->set_props(
			[
				'name'          => $request->get_title(),
				'slug'          => $request->get_slug(),
				'status'        => $request->get_status() === 'private' ? 'publish' : $request->get_status(),
				'date_created'  => $request->get_created_date(),
				'date_modified' => $request->get_modified_date(),
				'price'         => $request->get_budget(),
				'regular_price' => $request->get_budget(),
			]
		);

		// Update product.
		$product->save();
	}

	/**
	 * Deletes request.
	 *
	 * @param int $request_id Request ID.
	 */
	public function delete_request( $request_id ) {

		// Get product.
		$product = hp\get_first_array_value(
			wc_get_products(
				[
					'parent' => $request_id,
					'limit'  => 1,
				]
			)
		);

		// Delete product.
		if ( $product ) {
			$product->delete( true );
		}
	}

	/**
	 * Adds request attributes.
	 *
	 * @param array $attributes Attributes.
	 * @return array
	 */
	public function add_request_attributes( $attributes ) {

		// Add budget attribute.
		$attributes['budget'] = [
			'label'         => esc_html__( 'Budget', 'hivepress-requests' ),
			'editable'      => true,
			'filterable'    => true,
			'sortable'      => true,

			'display_areas' => [
				'view_block_primary',
				'view_page_primary',
			],

			'edit_field'    => [
				'label'     => esc_html__( 'Budget', 'hivepress-requests' ),
				'type'      => 'currency',
				'min_value' => 0,
				'required'  => true,
				'_order'    => 15,
			],

			'search_field'  => [
				'label'  => esc_html__( 'Budget', 'hivepress-requests' ),
				'type'   => 'number_range',
				'_order' => 100,
			],
		];

		return $attributes;
	}

	/**
	 * Adds offer fields.
	 *
	 * @param array $model Model arguments.
	 * @return array
	 */
	public function add_offer_fields( $model ) {
		if ( get_option( 'hp_offer_allow_bidding' ) ) {
			$model['fields']['price'] = [
				'label'     => hivepress()->translator->get_string( 'price' ),
				'type'      => 'currency',
				'min_value' => 0,
				'required'  => true,
				'_external' => true,
				'_order'    => 5,
			];
		}

		return $model;
	}

	/**
	 * Sets request context.
	 */
	public function set_request_context() {

		// Check authentication.
		if ( ! is_user_logged_in() || hp\is_rest() ) {
			return;
		}

		// Get cached request count.
		$request_count = hivepress()->cache->get_user_cache( get_current_user_id(), 'request_count', 'models/request' );

		if ( is_null( $request_count ) ) {

			// Get request count.
			$request_count = Models\Request::query()->filter(
				[
					'status__in' => [ 'private', 'publish' ],
					'user'       => get_current_user_id(),
				]
			)->get_count();

			// Cache request count.
			hivepress()->cache->set_user_cache( get_current_user_id(), 'request_count', 'models/request', $request_count );
		}

		// Set request context.
		hivepress()->request->set_context( 'request_count', $request_count );
	}

	/**
	 * Allows request access.
	 *
	 * @param array $caps All capabilities.
	 * @param array $cap Required capability.
	 * @param array $args Access arguments.
	 * @return array
	 */
	public function allow_request_access( $caps, $cap, $args ) {

		// Get capabilities.
		$required_cap  = hp\get_first_array_value( $cap );
		$requested_cap = hp\get_first_array_value( $args );

		if ( 'read_post' === $requested_cap && 'read_private_posts' === $required_cap ) {

			// Get request ID.
			$request_id = hp\get_last_array_value( $args );

			if ( $request_id && get_post_type( $request_id ) === 'hp_request' ) {

				// Add capability.
				$caps[ $required_cap ] = true;
			}
		}

		return $caps;
	}

	/**
	 * Alters user account menu.
	 *
	 * @param array $menu Menu arguments.
	 * @return array
	 */
	public function alter_user_account_menu( $menu ) {
		if ( hivepress()->request->get_context( 'request_count' ) ) {
			$menu['items']['requests_edit'] = [
				'route'  => 'requests_edit_page',
				'_order' => 10,
			];
		}

		return $menu;
	}

	/**
	 * Alters site header block.
	 *
	 * @param array $template Template arguments.
	 * @return array
	 */
	public function alter_site_header_block( $template ) {
		return hp\merge_trees(
			$template,
			[
				'blocks' => [
					'site_header_menu' => [
						'blocks' => [
							'request_submit_link' => [
								'type'   => 'part',
								'path'   => 'request/submit/request-submit-link',
								'_order' => 15,
							],
						],
					],
				],
			]
		);
	}

	/**
	 * Alters offer view blocks.
	 *
	 * @param array  $blocks Template blocks.
	 * @param object $template Template object.
	 * @return array
	 */
	public function alter_offer_view_blocks( $blocks, $template ) {
		$new_blocks = [];

		// Get request.
		$request = $template->get_context( 'request' );

		// Add actions.
		if ( $request && get_current_user_id() === $request->get_user__id() ) {
			$new_blocks['offer_actions_primary'] = [
				'blocks' => [
					'vendor_view_link'  => [
						'type' => 'content',
					],

					'offer_accept_form' => [
						'type'   => 'form',
						'form'   => 'offer_accept',
						'_order' => 10,
					],
				],
			];
		}

		// Add attributes.
		if ( get_option( 'hp_offer_allow_bidding' ) ) {
			$new_blocks['offer_attributes_primary'] = [
				'blocks' => [
					'offer_price' => [
						'type'   => 'part',
						'path'   => 'offer/view/offer-price',
						'_order' => 10,
					],
				],
			];
		}

		return hp\merge_trees(
			[ 'blocks' => $blocks ],
			[ 'blocks' => $new_blocks ]
		)['blocks'];
	}

	/**
	 * Alters order footer blocks.
	 *
	 * @param array  $blocks Template blocks.
	 * @param object $template Template object.
	 * @return array
	 */
	public function alter_order_footer_blocks( $blocks, $template ) {

		// Get order.
		$order = wc_get_order( $template->get_context( 'order' )->get_id() );

		// Get request.
		$request = $this->get_order_request( $order );

		if ( $request && $request->get_status() === 'private' ) {

			// Set template context.
			$template->set_context( 'request', $request );

			// Add template blocks.
			$blocks = hp\merge_trees(
				[ 'blocks' => $blocks ],
				[
					'blocks' => [
						'order_actions_primary' => [
							'blocks' => [
								'request_view_link' => [
									'type'   => 'part',
									'path'   => 'order/view/page/request-view-link',
									'_order' => 15,
								],
							],
						],
					],
				]
			)['blocks'];
		}

		return $blocks;
	}

	/**
	 * Alters vendor view page.
	 *
	 * @param array $template Template arguments.
	 * @return array
	 */
	public function alter_vendor_view_page( $template ) {
		if ( get_option( 'hp_request_allow_sending' ) ) {
			$template = hp\merge_trees(
				$template,
				[
					'blocks' => [
						'vendor_actions_primary' => [
							'blocks' => [
								'request_send_link' => [
									'type'   => 'part',
									'path'   => 'vendor/view/page/request-send-link',
									'_order' => 20,
								],
							],
						],
					],
				]
			);
		}

		return $template;
	}
}
