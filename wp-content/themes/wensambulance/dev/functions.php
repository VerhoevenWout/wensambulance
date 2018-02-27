<?php 

require_once ('lib/cpt.php');

// --------------------------------------------------------------------
// ADD MENU
add_theme_support('menus');
function register_my_menus() {
    $args = array( 
        'menu-1' => __( 'Main Menu' )
    );
    register_nav_menus( $args );
}
add_action( 'init', 'register_my_menus' );

// --------------------------------------------------------------------
// OPTIONS PAGE
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page();
	acf_set_options_page_title('Contact');
}

// --------------------------------------------------------------------
// REMOVE WYSIWYG FOR POST_TYPES
function remove_cpt_support(){
	$post_types = ['verhalen', 'wens_suggesties'];
	foreach ($post_types as $key => $post_type) {
		remove_post_type_support( $post_type, 'editor' );
	}
}
add_action( 'init', 'remove_cpt_support', 100 );
