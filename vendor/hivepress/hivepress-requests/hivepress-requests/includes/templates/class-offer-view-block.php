<?php
/**
 * Offer view block template.
 *
 * @package HivePress\Templates
 */

namespace HivePress\Templates;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Offer view block template class.
 *
 * @class Offer_View_Block
 */
class Offer_View_Block extends Template {

	/**
	 * Class constructor.
	 *
	 * @param array $args Template arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_trees(
			[
				'blocks' => [
					'offer_container' => [
						'type'       => 'container',
						'_order'     => 10,

						'attributes' => [
							'class' => [ 'hp-offer', 'hp-offer--view-block' ],
						],

						'blocks'     => [
							'offer_header'  => [
								'type'       => 'container',
								'tag'        => 'header',
								'_order'     => 10,

								'attributes' => [
									'class' => [ 'hp-offer__header' ],
								],

								'blocks'     => [
									'offer_image'   => [
										'type'   => 'part',
										'path'   => 'offer/view/offer-image',
										'_order' => 10,
									],

									'offer_summary' => [
										'type'       => 'container',
										'_order'     => 20,

										'attributes' => [
											'class' => [ 'hp-offer__summary' ],
										],

										'blocks'     => [
											'offer_bidder' => [
												'type'   => 'part',
												'path'   => 'offer/view/offer-bidder',
												'_order' => 10,
											],

											'offer_details' => [
												'type'   => 'container',
												'_order' => 20,

												'attributes' => [
													'class' => [ 'hp-offer__details' ],
												],

												'blocks' => [
													'offer_created_date'   => [
														'type'     => 'part',
														'path' => 'offer/view/offer-created-date',
														'_order'    => 10,
													],
												],
											],
										],
									],
								],
							],

							'offer_content' => [
								'type'       => 'container',
								'_order'     => 20,

								'attributes' => [
									'class' => [ 'hp-offer__content' ],
								],

								'blocks'     => [
									'offer_text' => [
										'type'   => 'part',
										'path'   => 'offer/view/offer-text',
										'_order' => 10,
									],
								],
							],

							'offer_footer'  => [
								'type'       => 'container',
								'tag'        => 'footer',
								'optional'   => true,
								'_order'     => 30,

								'attributes' => [
									'class' => [ 'hp-offer__footer' ],
								],

								'blocks'     => [
									'offer_attributes_primary'    => [
										'type'       => 'container',
										'blocks'     => [],
										'_order'     => 10,

										'attributes' => [
											'class' => [ 'hp-offer__attributes', 'hp-offer__attributes--primary' ],
										],
									],

									'offer_actions_primary'    => [
										'type'       => 'container',
										'optional'   => true,
										'_order'     => 20,

										'attributes' => [
											'class' => [ 'hp-offer__actions', 'hp-offer__actions--primary' ],
										],

										'blocks'     => [
											'vendor_view_link' => [
												'type'   => 'part',
												'path'   => 'offer/view/vendor-view-link',
												'_order' => 10,
											],
										],
									],
								],
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
