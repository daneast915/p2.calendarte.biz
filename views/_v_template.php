<!DOCTYPE html>
<html>
<head>
	<title><?php if(isset($title)) echo $title; ?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
					
	<!-- Controller Specific JS/CSS -->
	<?php if(isset($client_files_head)) echo $client_files_head; ?>
	
</head>

<body>	
	<header>
    	<h1>
    		<a class="header_link" title="<?=APP_NAME?>" href="/">
    			"<?=APP_NAME?>"
    		</a>
    	</h1>
	</header>

    <?php if ($user): ?>
    
    <!-- Menu for users who are logged in -->
	<nav class='site_navigation'>
        
        <ul class='user_links'>
			<li class='user_name'><?=$user->first_name?></li>
            <li><a href='/users/profile'>Profile</a></li>
            <li><a href='/users/logout'>Logout</a></li>
    	</ul>

    	<ul>
			<li><a href='/'>Home</a></li>
		
            <li><a href='/posts/add'>Add Post</a></li>
            <li><a href='/posts/index'>See Posts</a></li>
            <li><a href='/posts/users'>Follow Users</a></li>
        </ul>
        
        <hr class="clearme"/>
	
	</nav>
    <?php endif; ?>
	
	<br/>

	<?php if(isset($content)) echo $content; ?>
	
	<footer>
		<hr class='hr-thin'/>
		"What's Up?" -- Project 2, Dan East
		<br/>
		CSCI E15 - Dynamic Web Applications
		<br/>
		Harvard University Extension School
	</footer>

	<?php if(isset($client_files_body)) echo $client_files_body; ?>
</body>
</html>