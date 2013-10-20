<h2>Log In</h2>

<form method='POST' action='/users/p_login'>

    Email<br/>
    <input type='text' name='email'>
    <br/><br/>

    Password<br/>
    <input type='password' name='password'/>
    <br/><br/>
    
    <?php if (isset($error)): ?>
    	<div class='error'>
    		Login failed. Invalid Email or Password.
    	</div>
    	<br/>
    <?php endif; ?>

    <input type='submit' value='Log In'/>

</form>