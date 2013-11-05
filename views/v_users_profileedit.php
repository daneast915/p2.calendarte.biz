<h2>Edit Profile for <?=$user->first_name?></h2>

<section id="users_profileedit">

	<form method='POST' action='/users/profileedit'>
	
		<?php if (isset($error) && strlen($error) > 0): ?>
			<div class='error'>
				<?=$error?>
			</div>
			<br/>
		<?php endif; ?>
	
		<label for='first_name'>First Name</label>
		<input type='text' name='first_name' id='first_name' class='textbox' autofocus
				value='<?=$first_name?>' />
		<br><br>

		<label for='last_name'>Last Name</label>
		<input type='text' name='last_name' id='last_name' class='textbox'
				value='<?=$last_name?>' />
		<br><br>

		<label for='email'>Email Address</label>
		<input type='text' name='email' id='email' class='textbox'
				value='<?=$email?>' />
		<br><br>
	
		<input type='submit' value='Save' class='button'>

	</form>

</section>