<?php

// Register Custom Post Type
function create_posttype() {
	register_post_type( 'verhalen',
	// CPT Options
		array(
			'labels' => array(
				'name' => __( 'Verhalen' ),
				'singular_name' => __( 'Verhaal' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'verhalen'),
			'menu_icon' => 'dashicons-format-status',
			'supports' => array( 'editor','title'),
      		'taxonomies' => array('post_tag'),
		)
	);
	register_post_type( 'wens_suggesties',
	// CPT Options
		array(
			'labels' => array(
				'name' => __( 'Wens suggesties' ),
				'singular_name' => __( 'Wens suggestie' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'wens-suggesties'),
			'menu_icon' => 'dashicons-format-status',
			'supports' => array( 'editor','title'),
			'taxonomies' => array('post_tag'),
		)
	);
}
add_action( 'init', 'create_posttype' );