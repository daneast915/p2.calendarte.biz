<h2>Add a Post</h2>

<section id="new-post">

	<form method='POST' action='/posts/add' class="new-post-form">
	
		<?php if (isset($error) && strlen($error) > 0): ?>
			<div class='error'>
				<?=$error?>
			</div>
			<br/>
		<?php endif; ?>
		
		<textarea name='content' id='content' autofocus></textarea>
		<br/>
		<input type='submit' value='Post' class='button'></input>
	
	</form>

</section>