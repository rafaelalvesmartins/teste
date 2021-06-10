<div class="hp-listing-category__header">
	<?php
	echo ( new \HivePress\Blocks\Part(
		[
			'path'    => 'listing-category/view/listing-category-item-count',
			'context' => [ 'listing_category' => $listing_category ],
		]
	) )->render();
	?>
	<h1 class="hp-listing-category__name"><?php echo esc_html( $listing_category->get_name() ); ?></h1>
	<?php if ( $listing_category->get_description() ) : ?>
		<div class="hp-listing-category__description"><?php echo esc_html( $listing_category->get_description() ); ?></div>
	<?php endif; ?>
</div>
