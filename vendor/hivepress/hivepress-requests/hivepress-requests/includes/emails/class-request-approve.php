<?php
/**
 * Request approve email.
 *
 * @package HivePress\Emails
 */

namespace HivePress\Emails;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Request approve email class.
 *
 * @class Request_Approve
 */
class Request_Approve extends Email {

	/**
	 * Class constructor.
	 *
	 * @param array $args Email arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'subject' => esc_html__( 'Request Approved', 'hivepress-requests' ),
			],
			$args
		);

		parent::__construct( $args );
	}
}
