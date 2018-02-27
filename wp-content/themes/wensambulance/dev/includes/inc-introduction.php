<div class="row section introduction">

	<?php if ( !empty(get_sub_field('flex_home_introductie_repeater')) ) :
	while(have_rows('flex_home_introductie_repeater')): the_row();?>
		<div class="columns small-12 medium-4">
			<?php $image = get_sub_field('flex_home_introductie_repeater_image'); ?>
			<img src="<?= $image['sizes']['medium'] ?>" alt="">
			<h3>
				<?= the_sub_field('flex_home_introductie_repeater_header'); ?>
			</h3>
			<span>
				<?= the_sub_field('flex_home_introductie_repeater_text'); ?>
			</span>
			<a href="<?= the_sub_field('flex_home_introductie_repeater_link'); ?>" class="button secondary">Meer weten</a>
		</div>
    <?php endwhile;
	endif; ?>
	
</div>