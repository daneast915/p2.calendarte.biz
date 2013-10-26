<?php foreach ($posts as $post): ?>

<article>
	<h2><?=$post['first_name']?> <?=$post['last_name']?> posted:</h2>
	
	<p><?=$post['content']?></p>
	
	<time datetime="<?=Time::display($post['created'],'Y-m-d G:i')?>">
		<?=Time::display($post['created'])?>
	</time>
	
</article>

<?php endforeach; ?>