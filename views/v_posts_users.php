<h1>Follow Users</h1>

<?php foreach ($users as $user): ?>

	<!-- Print this users' name -->
    <div class='follow-name'>
	<?=$user['first_name']?> <?=$user['last_name']?>
    </div>

	<div class='follow-link'>
	<!-- If there exists a connection with this user, show an unfollow link -->
	<?php if (isset ($connections[$user['user_id']])): ?>
 		<a href="/posts/unfollow/<?=$user['user_id']?>">Unfollow</a>
        
    <!-- Otherwise, show the follow link -->
    <?php else: ?>
    	<a href="/posts/follow/<?=$user['user_id']?>">Follow</a>
        
    <?php endif; ?>
    </div>
    
    <br/>

<?php endforeach; ?>