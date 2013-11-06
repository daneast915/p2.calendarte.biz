<?php

// ini_set('display_errors', 'On');
// error_reporting(E_ALL);

class posts_controller extends base_controller {

	/*-------------------------------------------------------------------------------------------------
	
	-------------------------------------------------------------------------------------------------*/
	public function __construct() {
	
		parent::__construct();

		# careful with echos here!

		# Not logged in - redirect to the Main page
    	if (!$this->user)
    		Router::redirect('/');
	}
	
	/*-------------------------------------------------------------------------------------------------
	
	-------------------------------------------------------------------------------------------------*/
	public function index($param = NULL) {
	
		# Setup the View
		$this->template->content = View::instance('v_posts_index');
		$this->template->title = "Posts";
		
		# Set message based on the $param
		if (isset($param)) {
			switch ($param) {
				case 1:
					$this->template->content->message = "Your new post was added.<br/>";
					break;
				case 2:
					$this->template->content->message = "Your post was deleted.<br/>";
					break;
			}
		}
				
		# Get the posts of the followed users
		$posts = $this->userObj->get_followed_posts ($this->user->user_id);
		
		if (count($posts) == 0) {
			# Let them follow some users
			Router::redirect("/posts/users/1");
		}
		
		# Pass data to the View
		$this->template->content->posts = $posts;
		$this->template->content->user_id = $this->user->user_id;
		
		# Render the View
		echo $this->template;
		
	}

	/*-------------------------------------------------------------------------------------------------
	
	-------------------------------------------------------------------------------------------------*/
	public function add() {
	
		# Setup view
		$this->template->content = View::instance('v_posts_add');
		$this->template->title = "New Post";
		
		# Render templates
		if (!$_POST) {
			echo $this->template;
			return;
		}

		# Innocent until proven guilty
		$error = false;
		$this->template->content->error = '';
		
 		# Prevent SQL injection attacks by sanitizing the data the user entered in the form
		$_POST = DB::instance(DB_NAME)->sanitize($_POST);
			
		# Validate the post
		if (empty($_POST['content'])) {
			$this->template->content->error .= 'Post must not be empty.<br/>';
			$error = true;
		}

		# If any errors, display the page with the errors
		if ($error) {
			echo $this->template;
			return;
		}

		# Add a post for this user
		$_POST['content'] = $this->userObj->cleanse_data ($_POST['content']);
		$this->userObj->add_post ($this->user->user_id, $_POST);
		
		# Feedback
		Router::redirect("/posts/index/1");
	}
	
	/*-------------------------------------------------------------------------------------------------
	
	-------------------------------------------------------------------------------------------------*/
	public function delete() {
	
		# Delete this post
		$this->userObj->delete_post ($this->user->user_id, $_POST['post_id']);
			
		# Send them back
		Router::redirect("/posts/index/2");	
	}
	
	/*-------------------------------------------------------------------------------------------------
	
	-------------------------------------------------------------------------------------------------*/
	public function users($param = NULL) {
	
		# Setup the View
		$this->template->content = View::instance("v_posts_users");
		$this->template->title = "Users";
		
		# Set message based on the $param
		if (isset($param)) {
			switch ($param) {
				case 1:
					$this->template->content->message = "Follow some users to see their posts.<br/>";
					break;
			}
		}
				
		# Get a list of all other users
		$users = $this->userObj->get_all_other_users ($this->user->user_id);
		
		# Get a list of the followed users
		$connections = $this->userObj->get_followed_users ($this->user->user_id);
		
		# Pass data (users and connections) to the View
		$this->template->content->users = $users;
		$this->template->content->connections = $connections;
		
		# Render the View
		echo $this->template;
	}
	
	/*-------------------------------------------------------------------------------------------------
	
	-------------------------------------------------------------------------------------------------*/
	public function follow () {
	
		# Prepare the data array to be inserted
		$user_id_followed = $_POST['user_id_followed'];
		
		# Follow the user
		$this->userObj->follow_user ($this->user->user_id, $user_id_followed);
		
		# Send them back
		Router::redirect("/posts/users");
	}
	
	/*-------------------------------------------------------------------------------------------------
	
	-------------------------------------------------------------------------------------------------*/
	public function unfollow() {
	
		# Delete this connection
		$user_id_followed = $_POST['user_id_followed'];
		
		# Stop Following the user
		$this->userObj->unfollow_user ($this->user->user_id, $user_id_followed);
					
		# Send them back
		Router::redirect("/posts/users");	
	}
	
} #eoc