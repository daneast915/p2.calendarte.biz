<h2>Edit Profile for <?=$user->first_name?></h2>

<section id="users_signup">

<form method='POST' action='/users/p_profileedit'>
    
    <?php if (isset($error)): ?>
    	<div class='error'>
    		<?=$error?>
    	</div>
    	<br/>
    <?php endif; ?>
    
    <label for='first_name'>First Name</label>
    <input type='text' name='first_name' id='first_name' class='textbox'>
    <?=$first_name?>
    </input>
    <br><br>

    <label for='last_name'>Last Name</label>
    <input type='text' name='last_name' id='last_name' class='textbox'>
    <?=$last_name?>
    </input>
    <br><br>

    <input type='submit' value='Save' class='button'>

</form>

</section>