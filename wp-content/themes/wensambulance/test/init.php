<?php

namespace Volta;


class init {

	public function __construct(){

		//Add custom admin style 
		add_action( 'admin_enqueue_scripts', array($this, 'load_custom_wp_admin_style'));
		
		//Register menus
		$this->register_my_menus();
	}

	public function load_custom_wp_admin_style() {
	        wp_register_style( 'custom_wp_admin_css', get_template_directory_uri() . '/dist/css/style-admin.css', false, '1.0.0' );
	        wp_enqueue_style( 'custom_wp_admin_css' );
	}

	public function register_my_menus() {

	    register_nav_menus(
	        array(
	            'hoofd-menu' => __( 'Hoofdmenu' ),
	            'top-menu' => __( 'Topmenu' ),
	            'footer-menu' => __( 'Voetmenu' )
	        )
	    );
	}


}