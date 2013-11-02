<h2>Posts</h2>

<section id="posts_list">

<?php foreach ($posts as $post): ?>

<article>
	<h3><?=$post['first_name']?> <?=$post['last_name']?></h3>
	
    <div class="date_time">
		<time datetime="<?=Time::display($post['created'],'Y-m-d G:i')?>">
			<?=Time::display($post['created'])?>
		</time>
    </div>
	
	<p class="post_text"><?=$post['content']?></p>
	
</article>

<?php endforeach; ?>

</section>