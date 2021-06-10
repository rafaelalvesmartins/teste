<?php
/**
 * Offer make form block.
 *
 * @package HivePress\Blocks
 */

namespace HivePress\Blocks;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Offer make form block class.
 *
 * @class Offer_Make_Form
 */
class Offer_Make_Form extends Form {

	/**
	 * Class constructor.
	 *
	 * @param array $args Block arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'form' => 'offer_make',
			],
			$args
		);

		parent::__construct( $args );
	}

	/**
	 * Bootstraps block properties.
	 */
	protected function boot() {

		// Get request.
		$request = $this->get_context( 'request' );

		if ( $request ) {
			$this->values['request'] = $request->get_id();
		}

		parent::boot();
	}
}
