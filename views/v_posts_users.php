<h2>Follow Users</h2>

<section id="users-list">

<?php foreach ($users as $user): ?>

<article class="user">
    
	<div class='follow-link'>
	<!-- If there exists a connection with this user, show an unfollow link -->
	<?php if (isset ($connections[$user['user_id']])): ?>
 		<a href="/posts/unfollow/<?=$user['user_id']?>">Stop following</a>
        
    <!-- Otherwise, show the follow link -->
    <?php else: ?>
    	<a href="/posts/follow/<?=$user['user_id']?>">Follow</a>
        
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