<?php
/**
 * Strings configuration.
 *
 * @package HivePress\Configs
 */

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	'only_vendors_can_make_offers'  => esc_html__( 'Only vendors can make offers.', 'hivepress-requests' ),
	'notify_vendors_about_requests' => esc_html__( 'Notify vendors about new requests', 'hivepress-requests' ),
	'post_request'                  => esc_html__( 'Post a Request', 'hivepress-requests' ),
	'send_request'                  => esc_html__( 'Send Request', 'hivepress-requests' ),
];
