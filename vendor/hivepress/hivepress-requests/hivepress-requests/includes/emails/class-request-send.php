<?php
/**
 * Request send email.
 *
 * @package HivePress\Emails
 */

namespace HivePress\Emails;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Request send email class.
 *
 * @class Request_Send
 */
class Request_Send extends Email {

	/**
	 * Class constructor.
	 *
	 * @param array $args Email arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'subject' => esc_html__( 'Request Received', 'hivepress-requests' ),
			],
			$args
		);

		parent::__construct( $args );
	}
}
