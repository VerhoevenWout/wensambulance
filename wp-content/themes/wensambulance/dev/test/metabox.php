<?php

namespace Volta;

class metabox {

	public $name;

	public $pluralName;

	public $textdomain;

	public $title;

	public $cpt;

	public $context = 'normal';

	
	public function __construct( $cpt){

		$this->name = strtolower($this->name);
		$this->textdomain = strtolower($this->textdomain);
		$this->pluralName = strtolower($this->pluralName);

		if(!$this->title){
			$this->title = $this->name;
		}

		$this->cpt = $cpt;

	}

	public function custom_meta_box_markup(){

		wp_nonce_field( $this->name.'_inner_custom_box', $this->name.'_inner_custom_box_nonce' );

		$templateUrl = $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/'.get_template().'/stylebiss/metaboxes/templates/'.$this->name.'.php';

		if(file_exists($templateUrl)){
			include($templateUrl);
		}else{
			echo 'No tempalte found! for the '.$this->name.' metabox.';
		}

	}

	public function showBox(){
		return true;
	}


	public function save($post_id){
		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */

		if(get_post_type($post_id) != $this->cpt->name){
			return;
		}

		// Check if our nonce is set.
		if ( ! isset( $_POST[$this->name.'_inner_custom_box_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		$nonce = $_POST[$this->name.'_inner_custom_box_nonce'];
		if ( ! wp_verify_nonce( $nonce, $this->name.'_inner_custom_box' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$funcName = 'save'.$this->name;
		$this->$funcName($post_id);
	}
	

}