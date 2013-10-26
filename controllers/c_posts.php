<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

class posts_controller extends base_controller {

	public function __construct() {
		parent::__construct();

		# careful with echos here!
	}
	
	public function index() {
		# Setup the View
		$this->template->content = View::instance('v_posts_index');
		$this->template->title = "Posts";
		
		# Build the query
		$q = "SELECT
				posts.*,
				users.first_name,
				users.last_name
			FROM posts
			INNER JOIN users
				ON posts.user_id = users.user_id";
				
		# Run the query
		$posts = DB::instance(DB_NAME)->select_rows($q);
		
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
		echo "Your post has been added. <a href='/posts/add'>Add another</a>";
	}

}