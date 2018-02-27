<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 */

get_header(); ?>

<div class="row">
	<div class="columns small-12">
		<h1 class="page-header"><?php _e('Oeps...') ?></h1>
		<h2><?php _e('Deze pagina bestaat niet.') ?></h2>
		<a href="/"><?php _e('Ga terug naar de homepage') ?></a>
	</div>
</div>

<?php get_footer(); ?>