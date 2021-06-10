<?php
/**
 * Plugin Name: HivePress Requests
 * Description: Allow users to post requests and receive offers.
 * Version: 1.1.0
 * Author: HivePress
 * Author URI: https://hivepress.io/
 * Text Domain: hivepress-requests
 * Domain Path: /languages/
 *
 * @package HivePress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Register extension directory.
add_filter(
	'hivepress/v1/extensions',
	function( $extensions ) {
		$extensions[] = __DIR__;

		return $extensions;
	}
);

// Include the updates manager.
require_once __DIR__ . '/vendor/hivepress/hivepress-updates/hivepress-updates.php';
