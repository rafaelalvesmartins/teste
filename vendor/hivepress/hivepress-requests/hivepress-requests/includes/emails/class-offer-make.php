<?php
/**
 * Offer make email.
 *
 * @package HivePress\Emails
 */

namespace HivePress\Emails;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Offer make email class.
 *
 * @class Offer_Make
 */
class Offer_Make extends Email {

	/**
	 * Class constructor.
	 *
	 * @param array $args Email arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'subject' => esc_html__( 'Offer Received', 'hivepress-requests' ),
			],
			$args
		);

		parent::__construct( $args );
	}
}
