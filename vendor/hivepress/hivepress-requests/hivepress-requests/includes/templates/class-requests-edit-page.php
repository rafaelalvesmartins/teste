<?php
/**
 * Requests edit page template.
 *
 * @package HivePress\Templates
 */

namespace HivePress\Templates;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Requests edit page template class.
 *
 * @class Requests_Edit_Page
 */
class Requests_Edit_Page extends User_Account_Page {

	/**
	 * Class constructor.
	 *
	 * @param array $args Template arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_trees(
			[
				'blocks' => [
					'page_content' => [
						'blocks' => [
							'requests'           => [
								'type'   => 'requests',
								'mode'   => 'edit',
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
			$args
		);

		parent::__construct( $args );
	}
}
