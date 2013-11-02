<h2>Sign Up for <?=APP_NAME?></h2>

<section id="users_signup">

<form method='POST' action='/users/p_signup'>
    
    <?php if (isset($error)): ?>
    	<div class='error'>
    		Email already used. Please use another.
    	</div>
    	<br/>
    <?php endif; ?>
    
    <label for='first_name'>First Name</label>
    <input type='text' name='first_name' id='first_name' class='textbox'>
    <br><br>

    <label for='last_name'>Last Name</label>
    <input type='text' name='last_name' id='last_name' class='textbox'>
    <br><br>

    <label for='email'>Email</label>
    <input type='text' name='email' id='email' class='textbox'>
    <br><br>

    <label for='password'>Password</label>
    <input type='password' name='password' id='password' class='textbox'>
    <br><br>

    <input type='submit' value='Sign Up' class='button'>

</form>

<p class='alternative'>
<a href="/users/login">Already a user?  Log in!</a>
</p>

</section>