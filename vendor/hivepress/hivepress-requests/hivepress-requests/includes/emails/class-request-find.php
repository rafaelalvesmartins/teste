<?php
/**
 * Request find email.
 *
 * @package HivePress\Emails
 */

namespace HivePress\Emails;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Request find email class.
 *
 * @class Request_Find
 */
class Request_Find extends Email {

	/**
	 * Class constructor.
	 *
	 * @param array $args Email arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'subject' => esc_html__( 'Requests Available', 'hivepress-requests' ),
			],
			$args
		);

		parent::__construct( $args );
	}
}
