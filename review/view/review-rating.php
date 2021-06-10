<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( $review->get_rating() ) :
	?>
	<div class="hp-review__rating hp-rating">
		<div class="hp-rating__bar" data-component="progress" data-value="<?php echo esc_attr( $review->get_rating() ); ?>"></div>
		<div class="hp-rating__details">
			<span class="hp-rating__value"><?php echo esc_html( $review->display_rating() ); ?></span>
		</div>
	</div>
	<?php
endif;
