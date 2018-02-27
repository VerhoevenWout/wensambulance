<div class="row expanded banner">
	<div class="small-12">
		<div class="banner-carousel">
			<?php
			$images = get_field('banner_carousel_gallery');
			if( $images ): ?>
				<?php foreach( $images as $image ): ?>
			      <img src="<?php echo $image['sizes']['large']; ?>" alt="<?php echo $image['alt']; ?>" />
				<?php endforeach; ?>
			<?php endif; ?>

		</div>

		<div class="bannersub-carousel">
			<img class="banner-logo" src="<?= get_bloginfo('template_url') ?>/dist/img/logo-1.png">
			<h2>VOLIMUS</h2>
		</div>
	</div>
</div>