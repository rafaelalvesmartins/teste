<?php
/**
 * Request view page template.
 *
 * @package HivePress\Templates
 */

namespace HivePress\Templates;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Request view page template class.
 *
 * @class Request_View_Page
 */
class Request_View_Page extends Page_Sidebar_Right {

	/**
	 * Class constructor.
	 *
	 * @param array $args Template arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_trees(
			[
				'blocks' => [
					'page_columns' => [
						'attributes' => [
							'class' => [ 'hp-listing', 'hp-listing--view-page' ],
						],
					],

					'page_content' => [
						'blocks' => [
							'request_title'                => [
								'type'   => 'part',
								'path'   => 'request/view/page/request-title',
								'_order' => 10,
							],

							'request_details_primary'      => [
								'type'       => 'container',
								'optional'   => true,
								'_order'     => 20,

								'attributes' => [
									'class' => [ 'hp-listing__details', 'hp-listing__details--primary' ],
								],

								'blocks'     => [
									'request_created_date' => [
										'type'   => 'part',
										'path'   => 'request/view/request-created-date',
										'_order' => 10,
									],
								],
							],

							'request_attributes_secondary' => [
								'type'   => 'part',
								'path'   => 'request/view/page/request-attributes-secondary',
								'_order' => 30,
							],

							'request_description'          => [
								'type'   => 'part',
								'path'   => 'request/view/page/request-description',
								'_order' => 40,
							],

							'offers_container'             => [
								'type'       => 'section',
								'title'      => esc_html__( 'Offers', 'hivepress-requests' ),
								'_order'     => 100,

								'attributes' => [
									'id' => 'offers',
								],

								'blocks'     => [
									'offers' => [
										'type'   => 'offers',
										'_order' => 10,
									],
								],
							],
						],
					],

					'page_sidebar' => [
						'attributes' => [
							'data-component' => 'sticky',
						],

						'blocks'     => [
							'request_attributes_primary' => [
								'type'   => 'part',
								'path'   => 'request/view/page/request-attributes-primary',
								'_order' => 10,
							],

							'request_actions_primary'    => [
								'type'       => 'container',
								'_order'     => 20,

								'attributes' => [
									'class' => [ 'hp-listing__actions', 'hp-listing__actions--primary', 'hp-widget', 'widget' ],
								],

								'blocks'     => [
									'offer_make_modal'     => [
										'type'   => 'modal',
										'model'  => 'request',
										'title'  => esc_html__( 'Make an Offer', 'hivepress-requests' ),
										'_order' => 5,

										'blocks' => [
											'offer_make_form' => [
												'type'   => 'offer_make_form',
												'_order' => 10,

												'attributes' => [
													'class' => [ 'hp-form--narrow' ],
												],
											],
										],
									],

									'request_delete_modal' => [
										'type'   => 'modal',
										'title'  => esc_html__( 'Delete Request', 'hivepress-requests' ),
										'_order' => 5,

										'blocks' => [
											'request_delete_form' => [
												'type'   => 'form',
												'form'   => 'request_delete',
												'_order' => 10,

												'attributes' => [
													'class' => [ 'hp-form--narrow' ],
												],
											],
										],
									],

									'offer_make_link'      => [
										'type'   => 'part',
										'path'   => 'request/view/page/offer-make-link',
										'_order' => 10,
									],

									'request_delete_link'  => [
										'type'   => 'part',
										'path'   => 'request/view/page/request-delete-link',
										'_order' => 20,
									],
								],
							],

							'request_user'               => [
								'type'     => 'template',
								'template' => 'user_view_block',
								'_order'   => 30,
							],
						],
					],
				],
			],
			$args
		);

		parent::__construct( $args );
	}
}
