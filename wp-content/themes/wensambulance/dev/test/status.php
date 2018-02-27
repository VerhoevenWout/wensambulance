<?php

namespace Volta;

class status {

	public $name;

	public $pluralName;

	public $textdomain;


	public $args = array(
			
				'public'                    => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'exclude_from_search'       => true,
			);

	
	public function __construct(){

		$this->name = strtolower($this->name);
		$this->textdomain = strtolower($this->textdomain);
		$this->pluralName = strtolower($this->pluralName);

	}


	public function create(){

		$this->setStatusArg('label', _x( ucfirst($this->name), 'Status General Name', $this->textdomain ));
		$this->setStatusArg('label_count', _n_noop(  ucfirst($this->name).' (%s)',  $this->pluralName.' (%s)', $this->textdomain ));

		\register_post_status( str_replace(' ', '', $this->name), $this->args );

	}


	public function setStatusArg($argument, $option){

		$this->args[$argument] = $option;

	}	

}