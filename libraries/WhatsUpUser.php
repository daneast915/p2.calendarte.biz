<?php

/*-------------------------------------------------------------------------------------------------
 WhatsUpUser is an extension of the framework's User class.
 It contains convenience methods for database access.
-------------------------------------------------------------------------------------------------*/
class WhatsUpUser extends User {

	/*-------------------------------------------------------------------------------------------------
	Update the profile of a user
	
	@param  $token     - the user's token
	@param  $user_data - array of data to be used in the user's profile update
	@return DB update return
	-------------------------------------------------------------------------------------------------*/
	public function update_profile ($token, $user_data /* array */ ) {
	
		return DB::instance(DB_NAME)->update("users", $user_data, "WHERE token = '".$token."'");
		
	}
	
	/*-------------------------------------------------------------------------------------------------
	Insert a new user into the db and follow this user's own posts
	
	@param  $user_data - array of data to be used in the new user profile
	@return DB insert return
	-------------------------------------------------------------------------------------------------*/
	public function add_new_user ($user_data) {
	
		# Insert this user into the database
		$user_id = DB::instance(DB_NAME)->insert('users', $user_data);
		
		# Follow the user's own posts
		$users_users_data = Array(
			"created" => Time::now(),
			"user_id" => $user_id,
			"user_id_followed" => $user_id
			);
			
		return DB::instance(DB_NAME)->insert('users_users', $users_users_data);

	}
	
	/*-------------------------------------------------------------------------------------------------
	Get all of the posts for the users this user is following, including their own

	@param  $user_id - id of the user
	@return Array of posts
	-------------------------------------------------------------------------------------------------*/
	public function get_followed_posts ($user_id) {

		# Build the query
		$q = "SELECT
				posts.post_id,
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
			WHERE users_users.user_id = ".$user_id."
			ORDER BY posts.created DESC";
				
		# Run the query
		$posts = DB::instance(DB_NAME)->select_rows($q);
		
		return $posts;

	}
	
	/*-------------------------------------------------------------------------------------------------
	Insert a new post into the posts table

	@param  $user_id   - id of the user
	@param  $post_data - array of data for the new post
	@return DB insert return
	-------------------------------------------------------------------------------------------------*/
	public function add_post ($user_id, $post_data) {
	
		$_data = $post_data;
		
		# Associate this post with this user
		$_data['user_id'] = $user_id;
		
		# Unix timestamp of when this post was created / modified
		$_data['created'] = Time::now();
		$_data['modified'] = Time::now();	
		
		return DB::instance(DB_NAME)->insert('posts', $_data);
	
	}
	
	/*-------------------------------------------------------------------------------------------------
	Remove a post from the posts table

	@param  $user_id - id of the user
	@param  $post_id - id of the post to delete
	@return DB delete return
	-------------------------------------------------------------------------------------------------*/
	public function delete_post ($user_id, $post_id) {
	
		# Delete the post from the posts table
		$where_condition = 'WHERE post_id = '.$post_id;
		return DB::instance(DB_NAME)->delete('posts', $where_condition);
	
	}
	
	/*-------------------------------------------------------------------------------------------------
	Get a list of users other than this one

	@param  $user_id - id of the user
	@return Array containing all other users
	-------------------------------------------------------------------------------------------------*/
	public function get_all_other_users ($user_id) {
	
		# Build the query to get all the users
		$q = "SELECT *
			  FROM users
			  WHERE user_id <> ".$user_id."
			  ORDER BY last_name, first_name";
			  
		# Execute the query to get all the users.
		# Store the result array in the variable $users
		$users = DB::instance(DB_NAME)->select_rows($q);
		
		return $users;
			
	}
	
	/*-------------------------------------------------------------------------------------------------
	Get a list of users being followed

	@param  $user_id - id of the user
	@return Array containing followed users
	-------------------------------------------------------------------------------------------------*/
	public function get_followed_users ($user_id) {
	
		# Build the query to figure out what connections does the user already have?
		# ie. who are they following
		$q = "SELECT *
			  FROM users_users
			  WHERE user_id = ".$user_id;
			  
		# Execute this query with the select_array method
		# select_array will return the results in an array & use the "users_id_followed"
		# field as the index.  This will come in handy when we get to the view
		# Store the results (an array) in the variable $connections
		$connections = DB::instance(DB_NAME)->select_array ($q, 'user_id_followed');

		return $connections;	
		
	}
	
	/*-------------------------------------------------------------------------------------------------
	Follow a user

	@param  $user_id         - id of the user that is following
	@param  $user_id_followd - id of the user to be followed
	@return DB insert return
	-------------------------------------------------------------------------------------------------*/
	public function follow_user ($user_id, $user_id_followed) {
	
		# Build the data for the insert
		$data = Array(
			"created" => Time::now(),
			"user_id" => $user_id,
			"user_id_followed" => $user_id_followed
			);
			
		# Do the insert
		return DB::instance(DB_NAME)->insert('users_users', $data);
		
	}
	
	/*-------------------------------------------------------------------------------------------------
	Stop following a user

	@param  $user_id         - id of the user that is following
	@param  $user_id_followd - id of the user to stop following	
	@return DB delete return
	-------------------------------------------------------------------------------------------------*/
	public function unfollow_user ($user_id, $user_id_followed) {
	
		# Build the SQL statement
		$where_condition = "WHERE user_id = ".$user_id."
		                    AND user_id_followed = ".$user_id_followed;
		
		return DB::instance(DB_NAME)->delete('users_users', $where_condition);
		
	}
	
	/*-------------------------------------------------------------------------------------------------
	Validates an email address for format and uniqueness
	
	@param  $email        - the email address to validate
	@param  $check_unique - bool, whether or not to check for uniqueness
	@return Any error message text, or empty string
	-------------------------------------------------------------------------------------------------*/
	public function validate_email ($email, $check_unique = TRUE) {
	
		if (!empty($email)) {
			# Validate the email address for formatting
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				return "Email is invalid. Please use another.<br/>";
			}
			# Guarantee the email address is unique
			else if ($check_unique && !$this->confirm_unique_email($email)) {
				return "Email has already been used. Please use another.<br/>";
			}
		}
		
		return '';
			
	}

	/*-------------------------------------------------------------------------------------------------
	Check for invalid characters
	
	@param  $data - string data to check for invalid characters
	@return true if invalid characters found, or false if none found
	-------------------------------------------------------------------------------------------------*/
	public function check_for_invalid_chars ($data) {
	
		if (strpos($data, '<') !== FALSE ||
		    strpos($data, '>') !== FALSE ||
		    strpos($data, '/') !== FALSE ||
		    strpos($data, '\\') !== FALSE)
		    return true;
		    
		return false;
		
	}	
	
	/*-------------------------------------------------------------------------------------------------
	Cleanse user data - convert to HTML entities and strip slashes
	
	@param  $data - string data to be cleansed
	@return cleansed string
	-------------------------------------------------------------------------------------------------*/
	public function cleanse_data ($data) {
	
		return htmlentities(stripslashes(nl2br($data)),ENT_QUOTES,"Utf-8");
					
	}

} #eof