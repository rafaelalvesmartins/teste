<div class="row">
	<div class="header-hero__column col-sm-6 col-xs-12">
		<?php echo $content; ?>
	</div>
	<div class="header-hero__column col-sm-6 col-xs-12">
		<div class="header-hero__images" data-component="parallax">
			<?php
			foreach ( $images as $image ) :
				echo wp_get_attachment_image( $image->ID, 'medium_large' );
			endforeach;
			?>
		</div>
	</div>
</div>
