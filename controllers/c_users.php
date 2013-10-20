<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class users_controller extends base_controller {

    public function __construct() {
        parent::__construct();
        //echo "users_controller construct called<br><br>";
    } 

    public function index() {
        echo "This is the index page";
    }

    public function signup() {

        # Setup view
		$this->template->content = View::instance('v_users_signup');
		$this->template->title   = "Sign Up";

        # Render template
        echo $this->template;

    }

    public function p_signup() {

		# Dump out the results of POST to see what the form submitted
		//print_r($_POST);
		
		# More data we want stored with the user
		$_POST['created']  = Time::now();
		$_POST['modified'] = Time::now();

		# Encrypt the password  
		$_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);            

		# Create an encrypted token via their email address and a random string
		$_POST['token']    = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string()); 
    
		# Insert this user into the database
		$user_id = DB::instance(DB_NAME)->insert('users', $_POST);

		# For now, just confirm they've signed up - 
		# You should eventually make a proper View for this
		//echo "You're signed up";       
		
		Router::redirect('/users/login'); 
    }

    public function login($error = NULL) {
        # Setup view
		$this->template->content = View::instance('v_users_login');
		$this->template->title   = "Log In";
		
		# Pass data to the view
		$this->template->content->error = $error;

        # Render template
        echo $this->template;
    }

    public function p_login() {
    	# Encrypt the password  
		$_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);
		
		//echo "<pre>";
    	//print_r($_POST);
    	//echo "</pre>";
    	
    	$q = "SELECT token 
    		  FROM users 
    		  WHERE email = '".$_POST['email']."'
    		  AND password = '".$_POST['password']."'"; 
    	
    	$token = DB::instance(DB_NAME)->select_field($q);
    	
    	//echo "token='".$token."'";
    	
    	if (!$token) {
    		# Send them back to the login page
    		Router::redirect("/users/login/error");
    	} else {
    		# Store this token in a cookie using setcookie()
    		# NOTE: No echo before this!
    		setcookie ("token", $token, strtotime('+2 weeks'), '/');
    		
    		# Send them to the main page - or whereever
    		Router::redirect("/");
    	}
    	    	
	}

    public function logout() {
        # Generate and save a new token for next login
        $new_token = sha1(TOKEN_SALT.$this->user->email.Utils::generate_random_string());
        
        # Create the data array we'll use with the update method
        # In this case, we're only updating one field, so our array only has one entry
        $data = Array("token" => $new_token);
        
        # Do the update
        DB::instance(DB_NAME)->update("users", $data, "WHERE token = '".$this->user->token."'");
        
        # Delete their token cookie by setting it to a date in teh past - effectively logging them out
        setcookie("token", "", strtotime('-1 year'), '/');
        
        # Send them back to the main index.
        Router::redirect("/");
    }

    public function profile($user_name = NULL) { 
    
    	# If user is blank, they're not logged in; redirect them to the Login page
    	if (!$this->user) {
    		Router::redirect('/users/login');
    	}
    	
    	# If they weren't redirected away, continue ...

		# Setup view
		$content = View::instance('v_users_profile');
		$content->user_name = $user_name;

		//echo $content;
		$this->template->content = $content;
		$this->template->title = 'Profile';
		
		//$client_files_head = Array(
		//	'/css/profile.css',
		//	'/css/master.css');
		//$this->template->client_files_head = Utils::load_client_files($client_files_head);
		
		//$this->template->client_files_body = '';
		
		echo $this->template;
    }

} # end of the class