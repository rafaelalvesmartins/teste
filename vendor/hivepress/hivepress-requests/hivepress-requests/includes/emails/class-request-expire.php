<?php
/**
 * Request expire email.
 *
 * @package HivePress\Emails
 */

namespace HivePress\Emails;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Request expire email class.
 *
 * @class Request_Expire
 */
class Request_Expire extends Email {

	/**
	 * Class constructor.
	 *
	 * @param array $args Email arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'subject' => esc_html__( 'Request Expired', 'hivepress-requests' ),
			],
			$args
		);

		parent::__construct( $args );
	}
}
