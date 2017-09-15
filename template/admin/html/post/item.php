<article class="item">
	<header>
		<h2><i class="fa fa-newspaper-o"></i><?=escapeHTML($POST['ATTR']['NAME'])?></h2>
		<div>
			<span class="brackets item-id">#<?=$POST['ATTR']['ID']?></span>
			<a class="brackets" href="<?=Application::getAdminURL("user/update.php?id={$USER['ATTR']['ID']}")?>"><?=escapeHTML($USER['ATTR']['FULLNAME'])?></a>
			<time class="brackets" datetime="<?=$POST['ATTR']['TIME_INSERT']?>"><?=parseDatetime($POST['ATTR']['TIME_INSERT'], $Language->text('date_format'))?></time>
		</div>
	</header>
	<blockquote cite="<?=$POST['URL']?>">
		<p><?=excerpt($POST['BODY']['HTML']())?></p>
	</blockquote>

	<?php if($POST['ARGV']): ?>
		<ul class="arguments">
			<?php foreach($POST['ARGV'] as $argument => $value): ?>
				<li><strong><?=$argument?></strong>: <span class="blue"><?=escapeHTML($value)?></span></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<footer>
		<ul>
			<li><a href="<?=$POST['URL']?>" target="_blank" title="<?=$Language->text('select_post')?>"><i class="fa fa-external-link"></i><span class="hidden"><?=$Language->text('select_post')?></span></a></li>
			<li><a href="<?=Application::getAdminURL("post/update.php?id={$POST['ATTR']['ID']}")?>" title="<?=$Language->text('update_post')?>"><i class="fa fa-pencil-square-o"></i><span class="hidden"><?=$Language->text('update_post')?></span></a></li>
			<li><a href="<?=Application::getAdminURL("post/delete.php?id={$POST['ATTR']['ID']}")?>" title="<?=$Language->text('delete_post')?>"><i class="fa fa-trash-o"></i><span class="hidden"><?=$Language->text('delete_post')?></span></a></li>
		</ul>
	</footer>
</article>