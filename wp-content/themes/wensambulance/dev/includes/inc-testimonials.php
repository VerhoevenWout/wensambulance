<div class="row expanded section testimonials">
	<div class="columns small-12">
		<h2 class="section-header">Ervaringen</h2>

		<div class="testimonials-carousel">

			<?php if( have_rows('flex_home_testimonials_repeater') ): ?>
			<?php while ( have_rows('flex_home_testimonials_repeater') ) : the_row(); ?>
				<div>
					<h3>"
						<?php the_sub_field('flex_home_testimonials_repeater_text') ?>
						"
					</h3>
					<span>
						<?php the_sub_field('flex_home_testimonials_repeater_author') ?>
					</span>
				</div>
			<?php endwhile; ?>
			<?php endif; ?>

		</div>
	</div>
</div>