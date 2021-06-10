<?php
/**
 * Requests view page template.
 *
 * @package HivePress\Templates
 */

namespace HivePress\Templates;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Requests view page template class.
 *
 * @class Requests_View_Page
 */
class Requests_View_Page extends Page_Sidebar_Left {

	/**
	 * Class constructor.
	 *
	 * @param array $args Template arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_trees(
			[
				'blocks' => [
					'page_header'  => [
						'blocks' => [
							'request_search_form' => [
								'type'   => 'request_search_form',
								'_order' => 10,
							],

							'request_filter_link' => [
								'type'   => 'part',
								'path'   => 'request/view/request-filter-link',
								'_order' => 20,
							],
						],
					],

					'page_sidebar' => [
						'attributes' => [
							'data-component' => 'sticky',
						],

						'blocks'     => [
							'request_filter_container' => [
								'type'       => 'container',
								'_order'     => 10,

								'attributes' => [
									'class' => [ 'widget', 'hp-widget', 'hp-widget--listing-filter' ],
								],

								'blocks'     => [
									'request_filter_modal' => [
										'type'       => 'modal',
										'_order'     => 10,

										'attributes' => [
											'class' => [ 'hp-modal--mobile' ],
										],

										'blocks'     => [
											'request_filter_form' => [
												'type'   => 'form',
												'form'   => 'request_filter',
												'_order' => 10,

												'attributes' => [
													'class' => [ 'hp-form--narrow' ],
												],
											],
										],
									],
								],
							],
						],
					],

					'page_topbar'  => [
						'type'     => 'results',
						'optional' => true,

						'blocks'   => [
							'request_count'     => [
								'type'   => 'result_count',
								'_order' => 10,
							],

							'request_sort_form' => [
								'type'       => 'form',
								'form'       => 'request_sort',
								'_order'     => 20,

								'attributes' => [
									'class' => [ 'hp-form--pivot' ],
								],
							],
						],
					],

					'page_content' => [
						'blocks' => [
							'requests_container' => [
								'type'   => 'results',
								'_order' => 20,

								'blocks' => [
									'requests'           => [
										'type'   => 'requests',
										'_order' => 10,
									],

									'request_pagination' => [
										'type'   => 'part',
										'path'   => 'page/pagination',
										'_order' => 20,
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
