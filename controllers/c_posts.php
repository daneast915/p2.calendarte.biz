<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

class posts_controller extends base_controller {

	public function __construct() {
		parent::__construct();

		# careful with echos here!

    	if (!$this->user)
    		Router::redirect('/users/login');
	}
	
	public function index() {
		# Setup the View
		$this->template->content = View::instance('v_posts_index');
		$this->template->title = "Posts";
		
		# Build the query
		$q = "SELECT
				posts.content,
				posts.created,
				posts.user_id AS post_user_id,
				users_users.user_id AS follower_id,
				users.first_name,
				users.last_name
			FROM posts
			INNER JOIN users_users
				ON posts.user_id = users_users.user_id_followed
			INNER JOIN users
				ON posts.user_id = users.user_id
			WHERE users_users.user_id = ".$this->user->user_id."
			ORDER BY posts.created DESC";
				
		# Run the query
		$posts = DB::instance(DB_NAME)->select_rows($q);
		
		if (count($posts) == 0) {
			# Let them follow some users
			Router::redirect("/posts/users");
		}
		
		# Pass data to the View
		$this->template->content->posts = $posts;
		
		# Render the View
		echo $this->template;
		
	}

	public function add() {
		# Setup view
		$this->template->content = View::instance('v_posts_add');
		$this->template->title = "New Post";
		
		# Render templates
		echo $this->template;
	}

	public function p_add() {
		# Associate this post with this user
		$_POST['user_id'] = $this->user->user_id;
		
		# Unix timestamp of when this post was created / modified
		$_POST['created'] = Time::now();
		$_POST['modified'] = Time::now();
		
		# Insert
		DB::instance(DB_NAME)->insert('posts', $_POST);
		
		# Feedback
		Router::redirect("/posts/index");
	}

	public function users() {
		# Setup the View
		$this->template->content = View::instance("v_posts_users");
		$this->template->title = "Users";
		
		# Build the query to get all the users
		$q = "SELECT *
			  FROM users
			  ORDER BY last_name, first_name";
			  
		# Execute the query to get all the users.
		# Store the result array in the variable $users
		$users = DB::instance(DB_NAME)->select_rows($q);
		
		# Build the query to figure out what connections does the user already have?
		# ie. who are they following
		$q = "SELECT *
			  FROM users_users
			  WHERE user_id = ".$this->user->user_id;
			  
		# Execute this query with the select_array method
		# select_array ill return the results in an array & use the "users_id_followed"
		# field as the index.  This will come in handy when we get to the view
		# Store the results (an array) in teh variable $connections
		$connections = DB::instance(DB_NAME)->select_array ($q, 'user_id_followed');
		
		# Pass data (users and connections) to the View
		$this->template->content->users = $users;
		$this->template->content->connections = $connections;
		
		# Render the View
		echo $this->template;
	}
	
	public function follow ($user_id_followed) {
		# Prepare the data array to be inserted
		$data = Array(
			"created" => Time::now(),
			"user_id" => $this->user->user_id,
			"user_id_followed" => $user_id_followed
			);
			
		# Do the insert
		DB::instance(DB_NAME)->insert('users_users', $data);
		
		# Send them back
		Router::redirect("/posts/users");
	}
	
	public function unfollow($user_id_followed) {
		# Delete this connection
		$where_condition = 'WHERE user_id = '.$this->user->user_id.' AND user_id_followed = '.$user_id_followed;
		DB::instance(DB_NAME)->delete('users_users', $where_condition);
			
		# Send them back
		Router::redirect("/posts/users");	
	}
}