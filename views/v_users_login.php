<h2>Log in to "<?=APP_NAME?>"</h2>

<section id="users_login">

	<form method='POST' action='/users/login'>
	
		<?php if (isset($error) && strlen($error) > 0): ?>
			<div class='error'>
				<?=$error?>
			</div>
			<br/>
		<?php endif; ?>
	
		<?php if (isset($message)): ?>
			<div class='message'>
				<?=$message?>
			</div>
			<br/>
		<?php endif; ?>

		<label for='email'>Email Address</label>
		<input type='text' name='email' id='email' class='textbox' autofocus>
		<br/><br/>

		<label for='password'>Password</label>
		<input type='password' name='password' id='password' class='textbox'/>
		<br/><br/>

		<input type='submit' value='Log In' class='button'/>

	</form>
	
	<hr class='hr-alternative, hr-thin'/>

	<p class='alternative'>
		<a href="/users/signup">Not a user yet?  Sign up!</a>
	</p>

</section>

