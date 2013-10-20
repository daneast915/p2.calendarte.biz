<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class practice_controller extends base_controller {

    public function __construct() {
        parent::__construct();
        //echo "users_controller construct called<br><br>";
    } 

    public function index() {
        echo "This is the index page";
    }

    public function test_db() {
    /*
        $q = "INSERT INTO users SET first_name = 'Albert', last_name = 'Einstein'";
        
        DB::instance(DB_NAME)->query($q);
        
        $q = "UPDATE users SET email = 'albert@aol.com' WHERE first_name = 'Albert'";
        
        DB::instance(DB_NAME)->query($q);
        */
    
    /*    
        $data = Array(
        	'first_name' => 'Jen', 
        	'last_name' => 'Meyer',
        	'email' => 'tiggerchop@aol.com',
        	);
		
		DB::instance(DB_NAME)->insert('users', $data);
		*/
		
		$_POST['first_name'] = 'Jen';
		
		$_POST = DB::instance(DB_NAME)->sanitize($_POST);
		
		$q = "SELECT email FROM users WHERE first_name = '".$_POST['first_name']."'";
		$email = DB::instance(DB_NAME)->select_field($q);
		echo $email;
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
		$_POST['token'] = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string()); 
    
		# Insert this user into the database
		$user_id = DB::instance(DB_NAME)->insert('users', $_POST);

		# For now, just confirm they've signed up - 
		# You should eventually make a proper View for this
		echo "You're signed up";        
    }

    public function login() {
        /*
		Instantiate an Image object using the "new" keyword
		Whatever params we use when instantiating are passed to __construct 
		*/
		$imageObj = new Image('http://placekitten.com/500/500');

		/*
		Call the resize method on this object using the object operator (single arrow ->) 
		which is used to access methods and properties of an object
		*/
		$imageObj->resize(200,200);

		# Display the resized image
		$imageObj->display();
    }

    public function logout() {
        echo "This is the logout page";
    }

    public function profile($user_name = NULL) {

		$content = View::instance('v_users_profile');
		$content->user_name = $user_name;

		//echo $content;
		$this->template->content = $content;
		$this->template->title = 'Profile';
		
		$client_files_head = Array(
			'/css/profile.css',
			'/css/master.css');
		$this->template->client_files_head = Utils::load_client_files($client_files_head);
		
		//$this->template->client_files_body = '';
		
		echo $this->template;
    }

} # end of the class