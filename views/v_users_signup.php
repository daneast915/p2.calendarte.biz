<h2>Sign Up for "<?=APP_NAME?>"</h2>

<section id="users_signup">

	<form method='POST' action='/users/signup'>
	
		<?php if (isset($error) && strlen($error) > 0): ?>
			<div class='error'>
				<?=$error?>
			</div>
			<br/>
		<?php endif; ?>
	
		<label for='first_name'>First Name</label>
		<input type='text' name='first_name' id='first_name' class='textbox' autofocus
				value='<?=$first_name?>' />
		<br/><br/>

		<label for='last_name'>Last Name</label>
		<input type='text' name='last_name' id='last_name' class='textbox'
				value='<?=$last_name?>' /> 
		<br/><br/>

		<label for='email'>Email Address</label>
		<input type='text' name='email' id='email' class='textbox'
				value='<?=$email?>' /> 
		<br/><br/>

		<label for='password'>Password</label>
		<input type='password' name='password' id='password' class='textbox'
				value='<?=$password?>' /> 
		<br/><br/>

		<input type='submit' value='Sign Up' class='button'>

	</form>

	<hr class='hr-alternative, hr-thin'/>
	
	<p class='alternative'>
		<a href="/users/login">Already a user?  Log in!</a>
	</p>

</section>