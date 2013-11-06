<h2>Welcome to "<?=APP_NAME?>"</h2>

<section id="welcome">
	<p class='welcome-text'>
		Welcome!
		<strong>"What's Up?"</strong> 
		is a micro-blog you can use to keep up with your friends and family.
		Start a conversation and be in the know.
	</p>
	
	<br/>
	
	<label id='signup-label'>Not signed up yet?</label>
	<label id='login-label'>Already signed up?</label>
    <hr class="clearme" />

	<form method='GET' action='/users/signup' id='signup-link'>
		<input type='submit' value='Register' class='button'/>
	</form>
	<form method='GET' action='/users/login' id='login-link'>
		<input type='submit' value='Log In' class='button'/>
	</form>
    <hr class="clearme" />
    
	<hr class='hr-thin'/>

	<br/>
	
	<label>+1 Features</label>
	<ul>
		<li>Delete a post</li>
		<li>Edit and display basic profile info</li>
	</ul>

</section>
