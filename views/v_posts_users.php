<h2>Follow Users</h2>

<section id="users-list">
	
	<?php if (isset($message)): ?>
		<div class='message'>
			<?=$message?>
		</div>
		<br/>
	<?php endif; ?>
		
	<?php foreach ($users as $user): ?>

	<article class="user">
	
		<div class='follow-link'>
			<!-- If there exists a connection with this user, show an unfollow link -->
			<?php if (isset ($connections[$user['user_id']])): ?>
				<form method='POST' action='/posts/unfollow'>
					<input type="hidden" name="user_id_followed" value="<?=$user['user_id']?>" >
					<input type='submit' value='Stop following' class='listentry-button'/>
				</form>
		
			<!-- Otherwise, show the follow link -->
			<?php else: ?>
				<form method='POST' action='/posts/follow'>
					<input type="hidden" name="user_id_followed" value="<?=$user['user_id']?>" >
					<input type='submit' value='Follow' class='listentry-button'/>
				</form>
		
			<?php endif; ?>
		</div>
	
		<!-- Print this users' name -->
		<div 
			<?php if (isset ($connections[$user['user_id']])): ?>
				class="followed-name">
			<?php else: ?>
				class="notfollowed-name">
			<?php endif; ?>

			<?=$user['first_name']?> <?=$user['last_name']?>
		</div>
		
		<hr class="clearme"/>
	</article>

	<?php endforeach; ?>

</section>