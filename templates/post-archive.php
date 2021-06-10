<article <?php post_class( 'post--archive' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<header class="post__header">
			<div class="post__image">
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'ht_landscape_small' ); ?></a>
				<?php get_template_part( 'templates/post/post-date' ); ?>
			</div>
		</header>
	<?php endif; ?>
	<div class="post__content">
		<?php
		if ( has_category() ) :
			?>
			<div class="post__categories">
				<?php the_category( ' ' ); ?>
			</div>
			<?php
		endif;

		if ( get_the_title() ) :
			?>
			<h3 class="post__title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>
			<?php
		endif;

		if ( ! get_post_format() && get_the_content() ) :
			?>
			<div class="post__text"><?php the_excerpt(); ?></div>
			<?php
		endif;
		?>
	</div>
	<footer class="post__footer">
		<?php get_template_part( 'templates/post/post-details' ); ?>
		<a href="<?php the_permalink(); ?>" class="post__readmore"><i class="fas fa-arrow-right"></i></a>
	</footer>
</article>
