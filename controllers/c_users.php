<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

class users_controller extends base_controller {


    public function __construct() {
        parent::__construct();
        //echo "users_controller construct called<br><br>";
	
    } 

    public function index() {
    	# If user is blank, they're not logged in; redirect them to the Login page
    	if (!$this->user)
    		Router::redirect('/users/login');
		else
    		Router::redirect("/posts/index");	
	}
	
	private function validateEmail($email) {
		# Validate the email address
	    $q = "SELECT token 
    		  FROM users 
    		  WHERE email = '".$email."'"; 
    	
    	$token = DB::instance(DB_NAME)->select_field($q);
		
		if ($token) {
    		# Send back false for error
			return false;
		}
		
		return true;
	}

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

		# Innocent until proven guilty
		$error = false;
		$this->template->content->error = '';

		# Transfer POST data to content in case of error
		$this->template->content->first_name = $_POST['first_name'];
		$this->template->content->last_name = $_POST['last_name'];
		$this->template->content->email = $_POST['email'];
		$this->template->content->password = $_POST['password'];
		
		# Loop through the POST data to validate
		foreach($_POST as $field_name => $value) {
			# If a field is blank, add a message
			if ($value == "") {
				$this->template->content->error .= '"'.$field_name.'" must contain a value.<br/>';
				$error = true;
			}
		}
			
		# Validate the email address
	    if (!$this->validateEmail($_POST['email'])) {
    		# Send them back to the login page
			$this->template->content->error .= "Email has already been used. Please use another.<br/>";
			$this->template->content['email'] = '';
			$error = true;
		}

		# If any errors, display the page with the errors
		if ($error) {
			$this->template->content->first_name = $_POST['first_name'];
			$this->template->content->last_name = $_POST['last_name'];
			$this->template->content->email = $_POST['email'];
			$this->template->content->password = $_POST['email'];

			echo $this->template;
			return;
		}
	
		# More data we want stored with the user
		$_POST['created']  = Time::now();
		$_POST['modified'] = Time::now();

		# Encrypt the password  
		$_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);            

		# Create an encrypted token via their email address and a random string
		$_POST['token']    = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string()); 
    
		# Insert this user into the database
		$user_id = DB::instance(DB_NAME)->insert('users', $_POST);

		# Send them to the login screen
		Router::redirect('/users/login/1'); 
    }

    public function login($parm = NULL) {
    	if ($this->user)
			Router::redirect('/posts/index'); 
	
        # Setup view
		$this->template->content = View::instance('v_users_login');
		$this->template->title   = "Log In";
		
		if (isset($parm)) {
			switch ($parm) {
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

    	# Encrypt the password  
		$_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);
    	
    	$q = "SELECT token 
    		  FROM users 
    		  WHERE email = '".$_POST['email']."'
    		  AND password = '".$_POST['password']."'"; 
    	
    	$token = DB::instance(DB_NAME)->select_field($q);
    	
    	if (!$token) {
    		# Send them back to the login page
			$this->template->content->error = "Login failed. Invalid Email or Password.<br/>";
			echo $this->template;
			return;
    	} 
		
		# Store this token in a cookie using setcookie()
		# NOTE: No echo before this!
		setcookie ("token", $token, strtotime('+2 weeks'), '/');
		
		# Send them to the main page - or whereever
		Router::redirect("/posts/index");
	}

    public function logout() {
    	# If user is blank, they're not logged in; redirect them to the Login page
    	if (!$this->user)
    		Router::redirect('/users/login');

        # Generate and save a new token for next login
        $new_token = sha1(TOKEN_SALT.$this->user->email.Utils::generate_random_string());
        
        # Create the data array we'll use with the update method
        # In this case, we're only updating one field, so our array only has one entry
        $data = Array("token" => $new_token);
        
        # Do the update
        DB::instance(DB_NAME)->update("users", $data, "WHERE token = '".$this->user->token."'");
        
        # Delete their token cookie by setting it to a date in the past - effectively logging them out
        setcookie("token", "", strtotime('-1 year'), '/');
        
        # Send them back to the main index.
        Router::redirect("/users/login/2");
    }

    public function profile($user_name = NULL) { 
    	# If user is blank, they're not logged in; redirect them to the Login page
    	if (!$this->user)
    		Router::redirect('/users/login');

		# Setup view
		$content = View::instance('v_users_profile');
		$content->user_name = $user_name;

		//echo $content;
		$this->template->content = $content;
		$this->template->title = 'Profile';
		
		echo $this->template;
    }

    public function profileedit() { 
    	# If user is blank, they're not logged in; redirect them to the Login page
    	if (!$this->user)
    		Router::redirect('/users/login');

		# Setup view
		$content = View::instance('v_users_profileedit');
		$content->first_name = $this->user->first_name;
		$content->last_name = $this->user->last_name;
		$content->email = $this->user->email;
		
		//echo $content;
		$this->template->content = $content;
		$this->template->title = 'Edit Profile';
	
		if (!$_POST) {
			echo $this->template;
			return;
		}
		
		# Innocent until proven guilty
		$error = false;
		$this->template->content->error = '';
		
		# Loop through the POST data to validate
		foreach($_POST as $field_name => $value) {
			# If a field is blank, add a message
			if ($value == "") {
				$this->template->content->error .= '"'.$field_name.'" must contain a value.<br/>';
				$error = true;
			}
		}

		# If any errors, display the page with the errors
		if ($error) {
			echo $this->template;
			return;
		}

		# Validate the email address
	    if ($_POST['email'] != $this->user->email && !$this->validateEmail($_POST['email'])) {
    		# Send them back to the login page
			$this->template->content->error = "Email has already been used. Please use another.<br/>";
        	echo $this->template;
			return;
		}		
		
		# Passed validation

		# More data we want stored with the user
		$_POST['modified'] = Time::now();
		
		# Do the update
		DB::instance(DB_NAME)->update("users", $_POST, "WHERE token = '".$this->user->token."'");
		   
		# Send them back to the profile page.
		Router::redirect("/users/profile");
   	}

} # end of the class