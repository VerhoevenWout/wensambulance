<?php
/**
 * The Template for displaying all single posts
 */

get_header(); 
 while ( have_posts() ) : the_post(); 
?>

<div class="row">
	<div class="columns small-12">
		<h1 class="page-header"><?php the_title() ?></h1>
	</div>
</div>

<div class="row">
	<div class="columns small-12">
		<div class='wysiwyg'>
			<?php the_content(); ?>
		</div>
	</div>
</div>

<?php if( have_rows('flex_text') ): ?>
<?php while ( have_rows('flex_text') ) : the_row(); ?>

	<?php if( get_row_layout() == 'flex_text_full' ): ?>
		<div class="row section">
			<div class="columns small-12">
				<p>
					<?php the_sub_field('flex_text_full_width') ?>
				</p>
			</div>
		</div>
	<?php elseif( get_row_layout() == 'flex_image_left' ):  ?>
		<div class="row section">
			<div class="columns small-12 medium-6 xmedium-4">
				<?php $image = get_sub_field('flex_image_left_image')['url'] ?>
				<img src="<?= $image ?>" alt="">
			</div>
			<div class="columns small-12 medium-6 xmedium-8">
				<p>
					<?php the_sub_field('flex_image_left_text') ?>
				</p>
			</div>
		</div>
	<?php elseif( get_row_layout() == 'flex_image_right' ):  ?>
		<div class="row section">
			<div class="columns small-12 medium-6 xmedium-8">
				<p>
					<?php the_sub_field('flex_image_right_text') ?>
				</p>
			</div>
			<div class="columns small-12 medium-6 xmedium-4">
				<?php $image = get_sub_field('flex_image_right_image')['url'] ?>
				<img src="<?= $image ?>" alt="">
			</div>
		</div>
	<?php endif; ?>

<?php endwhile; ?>
<?php endif; ?>




<?php 
endwhile; 
get_footer(); 
?>