<?php 
    /**
    * Template Name: Suggesties
    */
get_header(); ?>

<div class="row">
	<div class="columns small-12">
		<h1 class="page-header"><?php post_type_archive_title();?></h1>
	</div>
</div>

<div class="row block-container section">

	<?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : '1'; ?>
	<?php $args = array(
		'post_type' => 'wens_suggesties', 
		'post_status' => 'publish',
		'order' => 'ASC', 
		'has_archive' => true,
		'paged' => $paged,
		'posts_per_page'=> '5',
	);
	$postslist = new WP_Query( $args );?>

	<?php if ( $postslist->have_posts()) :
	while ( $postslist->have_posts() ) : $postslist->the_post(); ?>
		<?php $thumb = get_field('wens_suggestions_image')['url']; ?>
		<?php 
			$excerpt = get_field('wens_suggestions_excerpt');
			$excerpt = preg_replace(" ([.*?])",'',$excerpt);
			$excerpt = strip_shortcodes($excerpt);
			$excerpt = strip_tags($excerpt);
		?>
		<?php $post_date = get_the_date( 'j F Y' ); ?>

		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<div class="columns small-12 medium-6 xmedium-4">
				<img src="<?= $thumb ?>" alt="">
				<h3>
					<?php the_title(); ?>
				</h3>
				<p>
					<?= $excerpt ?>
				</p>
				<p><?= $post_date ?></p>
			</div>
		</a>
	<?php endwhile; endif; ?>
	
</div>

<?php get_footer(); ?>