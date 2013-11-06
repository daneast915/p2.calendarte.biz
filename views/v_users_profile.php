<h2>Profile for <?=$user->first_name?></h2>

<section id="users_profile">
	
	<?php if (isset($message)): ?>
		<div class='message'>
			<?=$message?>
		</div>
		<br/>
	<?php endif; ?>

    <label for='first_name'>First Name</label>
    <div id='first_name'><?=$user->first_name?></div>
    <br/>

    <label for='last_name'>Last Name</label>
    <div id='last_name'><?=$user->last_name?></div>
    <br/>

    <label for='email'>Email Address</label>
    <div id='email'><?=$user->email?></div>
    <br/>
    
    <hr class='hr-thin'/>

    <p class='alternative'>
    <a href="/users/profileedit">Edit Profile</a>
    </p>

</section>