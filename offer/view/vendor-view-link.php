<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>
<a href="<?php echo esc_url( hivepress()->router->get_url( 'vendor_view_page', [ 'vendor_id' => $vendor->get_id() ] ) ); ?>" class="hp-offer__action hp-offer__action--vendor hp-link">
	<i class="hp-icon fas fa-eye"></i>
	<span><?php esc_html_e( 'View Profile', 'experthive' ); ?></span>
</a>