<?php

namespace Volta;

class cpt {

	public $name;

	public $readName;

	public $pluralReadName;

	public $textdomain;

	public $postVars;

	public $postMeta;


	public $args = array(
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);

	
	public function __construct($theme){

		$this->name = strtolower($this->name);
		$this->Readname = strtolower($this->readName);
		$this->pluralReadName = strtolower($this->pluralName);
		$this->textdomain = strtolower($this->textdomain);

		$this->stylebiss = $theme;

		if($this->metaboxes){
			$this->initMetaboxes();
		}

	}


	public function create(){

		$labels = array(
			'name'                  => _x( ucfirst($this->pluralReadName), 'Post Type General Name', $this->textdomain ),
			'singular_name'         => _x( ucfirst($this->readName), 'Post Type Singular Name', $this->textdomain ),
			'menu_name'             => __( ucfirst($this->pluralReadName), $this->textdomain ),
			'name_admin_bar'        => __( ucfirst($this->readName), $this->textdomain ),
			'parent_item_colon'     => __( 'Parent Item:', $this->textdomain ),
			'all_items'             => __( 'Alle '.$this->pluralReadName, $this->textdomain ),
			'add_new_item'          => __( 'Maak nieuwe '.$this->readName , $this->textdomain ),
			'add_new'               => __( 'Maak nieuwe '.$this->readName , $this->textdomain ),
			'new_item'              => __( 'Nieuwe '.$this->readName , $this->textdomain ),
			'edit_item'             => __( 'Bewerk '.$this->readName , $this->textdomain ),
			'update_item'           => __( ucfirst($this->readName).' bijwerken', $this->textdomain ),
			'view_item'             => __( 'Bekijk '.$this->readName , $this->textdomain ),
			'search_items'          => __( 'Zoek '.$this->readName , $this->textdomain ),
			'not_found'             => __( 'Niet gevonden', $this->textdomain ),
			'not_found_in_trash'    => __( 'Niet gevonden in prullenmand', $this->textdomain ),
			'items_list'            => __( ucfirst($this->pluralReadName).' lijst', $this->textdomain ),
			'items_list_navigation' => __( '$this->readName lijst navigatie ', $this->textdomain ),
			'filter_items_list'     => __( 'Filter '.$this->pluralReadName.' lijst', $this->textdomain ),
		);

		$this->args['label'] = __( $this->readName, $this->textdomain );
		$this->args['labels'] = $labels;

		\register_post_type( $this->name, $this->args );
	}


	public function setPtArg($argument, $option){

		$this->args[$argument] = $option;

	}	


	public function removePublish(){

		add_action( 'add_meta_boxes', array($this, 'my_remove_publish_metabox' ));

	}

	public function my_remove_publish_metabox() {

	    remove_meta_box( 'submitdiv', $this->name , 'side' );

	}

	public function getPostVars(){

		$this->postVars = get_post();
		
	}

	public function getPostMeta(){

		
		$this->getPostVars();
		
		$this->postMeta = get_post_meta($this->postVars->ID);
		
	}

	public function underceptSave(){

		$this->newPostData = array();
		$this->newPostMeta = array();
		$this->updatePostMeta = array();

		add_filter( 'wp_insert_post_data', array($this, 'saveCpt'), 10 , 2 );
		
	}	


	public function saveCpt($data, $postArr){

		if($postArr['post_type'] == $this->name){

			$this->getPostMeta();
			
			$func = 'save'.ucfirst($this->name);
			$this->$func($postArr, $_REQUEST);

			foreach ($this->newPostData as $key => $value) {
				$data[$key] = $value;
			}

			foreach ($this->newPostMeta as $key => $value) {
				add_post_meta($postArr['ID'], $key , $value );
			}

			foreach ($this->updatePostMeta as $key => $value) {
				update_post_meta($postArr['ID'], $key , $value );
			}

		}

		return $data;
	}

	public function initMetaboxes(){

		foreach ($this->metaboxes as $box) {
			$classname = '\metaboxes\\'.$box;
			$boxname = $box.'Metabox';
			$this->$boxname = new $classname($this);
		}

		add_action('add_meta_boxes', array( $this, 'add_custom_meta_boxes' ));

	}

	public function add_custom_meta_boxes(){

		$this->getPostMeta();

		foreach ($this->metaboxes as $box) {
			$classname = '\metaboxes\\'.$box;
			$boxname = $box.'Metabox';

			if($this->$boxname->showBox()){
				add_meta_box($this->$boxname->name, $this->$boxname->title, array( $this->$boxname, "custom_meta_box_markup"), $this->name , $this->$boxname->context, "high", null);
			}
		}
	}


}