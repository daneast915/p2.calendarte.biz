<!--
<?php if(isset($user_name)): ?>
    <h1>This is the profile for <?=$user_name?></h1>
<?php else: ?>
    <h1>No user has been specified</h1>
<?php endif; ?>
-->

<h2>Profile for <?=$user->first_name?></h2>

<section id="users_profile">

    <label for='first_name'>First Name</label>
    <div id='first_name'><?=$user->first_name?></div>
    <br>

    <label for='last_name'>Last Name</label>
    <div id='last_name'><?=$user->last_name?></div>
    <br>

    <label for='email'>Email</label>
    <div id='email'><?=$user->email?></div>
    <br>

    <p class='alternative'>
    <a href="/users/profileedit">Edit Profile</a>
    </p>

</section>