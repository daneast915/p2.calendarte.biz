<?php

// ini_set('display_errors', 'On');
// error_reporting(E_ALL);

class users_controller extends base_controller {


	/*-------------------------------------------------------------------------------------------------
	Constructor
	-------------------------------------------------------------------------------------------------*/
    public function __construct() {
    
        parent::__construct();
	
    } 

	/*-------------------------------------------------------------------------------------------------
	users/index controller method
	-------------------------------------------------------------------------------------------------*/
    public function index() {
    
    	# If user is blank, they're not logged in; redirect them to the Main page
    	if (!$this->user)
    		Router::redirect('/');
		else
    		Router::redirect("/posts/index");	
	}

 	/*-------------------------------------------------------------------------------------------------
	users/signup controller method
	-------------------------------------------------------------------------------------------------*/
   public function signup() {

        # Setup view
		$this->template->content = View::instance('v_users_signup');;	
		$this->template->title   = "Sign Up";

        # Render template
		if (!$_POST) {
			$this->template->content->first_name = '';
			$this->template->content->last_name = '';
			$this->template->content->email = '';
			$this->template->content->password = '';
			
        	echo $this->template;
			return;
    	}
		
 		# Prevent SQL injection attacks by sanitizing the data the user entered in the form
		$_POST = DB::instance(DB_NAME)->sanitize($_POST);

		# Innocent until proven guilty
		$error = false;
		$this->template->content->error = '';
		
		# Array for field names
		$field_names = Array(
			"first_name" => "First Name",
			"last_name" => "Last Name",
			"email" => "Email Address",
			"password" => "Password"
			);
		
		# Loop through the POST data to validate
		foreach($_POST as $field_name => $value) {
			# If a field is blank, add a message
			if ($value == "") {
				$this->template->content->error .= $field_names[$field_name].' must contain a value.<br/>';
				$error = true;
			}
			
			# If a field contains invalid characters, add a message
			if ($this->userObj->check_for_invalid_chars ($value))  {
				$this->template->content->error .= $field_names[$field_name].' contains invalid characters.<br/>';
				$_POST[$field_name] = '';
				$error = true;				
			}
		}
		
		# Sanitize the data
		$_POST['first_name'] = $this->userObj->cleanse_data ($_POST['first_name']);
		$_POST['last_name'] = $this->userObj->cleanse_data ($_POST['last_name']);
		$_POST['email'] = $this->userObj->cleanse_data ($_POST['email']);
		
		$email = $_POST['email'];
		
		# Validate the email address for format and uniqueness
		$email_error = $this->userObj->validate_email ($email);
		if (!empty($email_error)) {
			$this->template->content->error .= $email_error;
			$email = '';
			$error = true;
		}

		# If any errors, display the page with the errors
		if ($error) {
			$this->template->content->first_name = $_POST['first_name'];
			$this->template->content->last_name = $_POST['last_name'];
			$this->template->content->email = $email;
			$this->template->content->password = '';

			echo $this->template;
			return;
		}
	
		# More data we want stored with the user
		$_POST['created']  = Time::now();
		$_POST['modified'] = Time::now();

		# Encrypt the password  
		$_POST['password'] = User::hash_password($_POST['password']);  

		# Create an encrypted token via their email address and a random string
		$_POST['token']    = User::generate_token($email); 
    
		# Insert this user into the database
		$this->userObj->add_new_user ($_POST);

		# Send them to the login screen
		Router::redirect('/users/login/1'); 
    }

	/*-------------------------------------------------------------------------------------------------
	users/login controller method
	-------------------------------------------------------------------------------------------------*/
    public function login($param = NULL) {
    
    	if ($this->user)
			Router::redirect('/posts/index'); 
	
        # Setup view
		$this->template->content = View::instance('v_users_login');
		$this->template->title   = "Log In";
		
		# Set message based on the $param
		if (isset($param)) {
			switch ($param) {
				case 1:
					$this->template->content->message = "Thanks for signing up. Please log in.<br/>";
					break;
				case 2:
					$this->template->content->message = "You've logged out.<br/>";
					break;
			}
		}

        # Render template
		if (!$_POST) {
			echo $this->template;
			return;
		}
		
 		# Prevent SQL injection attacks by sanitizing the data the user entered in the form
		$_POST = DB::instance(DB_NAME)->sanitize($_POST);

		# Attempt to login
		$token = $this->userObj->login ($_POST['email'], $_POST['password']);
    	
    	if (!$token) {
    		# Send them back to the login page
			$this->template->content->error = "Login failed. Invalid Email Address or Password.<br/>";
			echo $this->template;
			return;
    	} 
		
		# Send them to the main page - or whereever
		Router::redirect("/posts/index");
	}

	/*-------------------------------------------------------------------------------------------------
	users/logout controller method
	-------------------------------------------------------------------------------------------------*/
    public function logout() {
    
    	# If user is blank, they're not logged in; redirect them to the Login page
    	if (!$this->user)
    		Router::redirect('/users/login');

		# Attempt to logout
		$this->userObj->logout ($this->user->email);
        
        # Send them back to the main index.
        Router::redirect("/users/login/2");
    }

	/*-------------------------------------------------------------------------------------------------
	users/profile controller method
	-------------------------------------------------------------------------------------------------*/
    public function profile($param = NULL) { 
    
    	# If user is blank, they're not logged in; redirect them to the Login page
    	if (!$this->user)
    		Router::redirect('/users/login');

		# Setup view
		$this->template->content = View::instance('v_users_profile');
		$this->template->title = 'Profile';
		
		# Set message based on the $param
		if (isset($param)) {
			switch ($param) {
				case 1:
					$this->template->content->message = "Your profile was updated.<br/>";
					break;
			}
		}
				
		echo $this->template;
    }

	/*-------------------------------------------------------------------------------------------------
	users/profileedit controller method
	-------------------------------------------------------------------------------------------------*/
    public function profileedit() { 
    
    	# If user is blank, they're not logged in; redirect them to the Login page
    	if (!$this->user)
    		Router::redirect('/users/login');

		# Setup view
		$content = View::instance('v_users_profileedit');
		$content->first_name = $this->user->first_name;
		$content->last_name = $this->user->last_name;
		$content->email = $this->user->email;
		
		$this->template->content = $content;
		$this->template->title = 'Edit Profile';
	
		if (!$_POST) {
			echo $this->template;
			return;
		}
		
 		# Prevent SQL injection attacks by sanitizing the data the user entered in the form
		$_POST = DB::instance(DB_NAME)->sanitize($_POST);
		
		# Innocent until proven guilty
		$error = false;
		$this->template->content->error = '';
		
		# Array for field names
		$field_names = Array(
			"first_name" => "First Name",
			"last_name" => "Last Name",
			"email" => "Email Address"
			);
					
		# Loop through the POST data to validate
		foreach($_POST as $field_name => $value) {
			# If a field is blank, add a message
			if ($value == "") {
				$this->template->content->error .= $field_names[$field_name].' must contain a value.<br/>';
				$error = true;				
			}
			
			if ($this->userObj->check_for_invalid_chars ($value))  {
				$this->template->content->error .= $field_names[$field_name].' contains invalid characters.<br/>';
				$_POST[$field_name] = '';
				$error = true;				
			}
		}
		
		# Sanitize the fields
		$_POST['first_name'] = $this->userObj->cleanse_data ($_POST['first_name']);
		$_POST['last_name'] = $this->userObj->cleanse_data ($_POST['last_name']);
		$_POST['email'] = $this->userObj->cleanse_data ($_POST['email']);
		
		$email = $_POST['email'];

		# Validate the email address for format and uniqueness
		$email_error = $this->userObj->validate_email ($email, FALSE);
		if (!empty($email_error)) {
			$this->template->content->error .= $email_error;
			$email = '';
			$error = true;
		}
		
		if (!empty($email) && $email != $this->user->email) {
			if (!$this->confirm_unique_email($email)) {
				$this->template->content->error .=  "Email has already been used. Please use another.<br/>";
				$email = '';
				$error = true;
			}
		}

		# If any errors, display the page with the errors
		if ($error) {
			$this->template->content->first_name = $_POST['first_name'];
			$this->template->content->last_name = $_POST['last_name'];
			$this->template->content->email = $email;
			
			echo $this->template;
			return;
		}
		
		# Passed validation

		# More data we want stored with the user
		$_POST['modified'] = Time::now();
		
		# Do the update
		$this->userObj->update_profile ($this->user->token, $_POST);
		   
		# Send them back to the profile page.
		Router::redirect("/users/profile/1");
   	}

} # end of the class