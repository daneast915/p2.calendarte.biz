<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class index_controller extends base_controller {
	
	/*-------------------------------------------------------------------------------------------------

	-------------------------------------------------------------------------------------------------*/
	public function __construct() {
		parent::__construct();
	} 
		
	/*-------------------------------------------------------------------------------------------------
	Accessed via http://localhost/index/index/
	-------------------------------------------------------------------------------------------------*/
	public function index() {
		
    	# If user is blank, they're not logged in; redirect them to the Login page
    	if (!$this->user)
    		Router::redirect('/users/login');
		else
    		Router::redirect("/posts/index");	

	} # End of method
	
	
} # End of class
