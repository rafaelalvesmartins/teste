<?php
/**
 * Request reject email.
 *
 * @package HivePress\Emails
 */

namespace HivePress\Emails;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Request reject email class.
 *
 * @class Request_Reject
 */
class Request_Reject extends Email {

	/**
	 * Class constructor.
	 *
	 * @param array $args Email arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'subject' => esc_html__( 'Request Rejected', 'hivepress-requests' ),
			],
			$args
		);

		parent::__construct( $args );
	}
}
