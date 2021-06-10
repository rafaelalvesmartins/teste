<?php
/**
 * Meta boxes configuration.
 *
 * @package HivePress\Configs
 */

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	'request_settings' => [
		'title'  => hivepress()->translator->get_string( 'settings' ),
		'screen' => 'request',
		'model'  => 'request',
		'fields' => [],
	],
];
