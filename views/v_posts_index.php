<h2>Posts</h2>

<section id="posts_list">

<?php foreach ($posts as $post): 
	$canDelete = ($post['post_user_id'] == $user_id);
?>

<article>
	<h3><?=$post['first_name']?> <?=$post['last_name']?></h3>
	
    <div class="date_time">
		<time datetime="<?=Time::display($post['created'],'Y-m-d G:i')?>">
			<?=Time::display($post['created'])?>
		</time>
    </div>
	
    <?php if ($canDelete): ?>
    	<div class='delete-link'>
			<form method='POST' action='/posts/delete'>
				<input type="hidden" name="post_id" value="<?=$post['post_id']?>" >
    			<input type='submit' value='Delete' class='listentry-button'/>
    		</form>
    	</div>
    <?php endif; ?>

	<p class="post_text"><?=$post['content']?></p>
	
</article>

<?php endforeach; ?>

</section>