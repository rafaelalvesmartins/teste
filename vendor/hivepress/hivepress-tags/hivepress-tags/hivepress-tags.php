<?php
/**
 * Plugin Name: HivePress Tags
 * Description: Allow users to set listing tags.
 * Version: 1.0.1
 * Author: HivePress
 * Author URI: https://hivepress.io/
 * Text Domain: hivepress-tags
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
