<div class="row expanded section stories">
	<div class="columns small-12">
		<h2 class="section-header">Verhalen</h2>
		<div class="stories-carousel">

			<?php
			$args = array(
				'order' => 'DESC', 
				'posts_per_page'=>-1,
				'post_type'=> 'verhalen',
			);
			$postslist = get_posts($args); $i = 0;
			foreach ($postslist as $post): setup_postdata($post); ?>
				<?php $thumb = get_field('wens_suggestions_image')['url']; ?>
				<div>
					<a href="<?php the_permalink(); ?>">
						<img src="<?php echo $image['sizes']['thumb']; ?>" alt="<?php echo $image['alt']; ?>" />
						<h3><?php the_title(); ?></h3>
					</a>
				</div>
				<?php $i++; wp_reset_postdata(); ?>
			<?php endforeach; ?>

		</div>
	</div>
</div>