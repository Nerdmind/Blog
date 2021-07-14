<article class="item">
	<header>
		<h2>
			<span class="item-id">#<?=$POST['ATTR']['ID']?></span>
			<i class="fa fa-newspaper-o"></i><?=escapeHTML($POST['ATTR']['NAME'])?>
		</h2>
		<ul class="item-meta">
			<li>
				<i class="fa fa-clock-o"></i>
				<time datetime="<?=$POST['ATTR']['TIME_INSERT']?>"><?=parseDatetime($POST['ATTR']['TIME_INSERT'], $Language->text('date_format'))?></time>
			</li>
			<li>
				<i class="fa fa-user"></i>
				<a href="<?=Application::getAdminURL("user/update.php?id={$USER['ATTR']['ID']}")?>" title="<?=$Language->text('update_user')?>"><?=escapeHTML($USER['ATTR']['FULLNAME'])?></a>
			</li>
			<?php if($CATEGORY): ?>
			<li class="item-meta-right">
				<i class="fa fa-tag"></i>
				<a href="<?=Application::getAdminURL("category/update.php?id={$CATEGORY['ATTR']['ID']}")?>" title="<?=$Language->text('update_category')?>"><?=escapeHTML($CATEGORY['ATTR']['NAME'])?></a>
			</li>
			<?php endif ?>
		</ul>
	</header>
	<blockquote cite="<?=$POST['URL']?>">
		<?php if(isset($POST['FILE']['LIST'][0])): ?>
			<img class="item-image" src="<?=$POST['FILE']['LIST'][0]?>" alt="" />
		<?php endif; ?>
		<p><?=excerpt($POST['BODY']['HTML']())?></p>
	</blockquote>

	<?php if($POST['ARGV']): ?>
		<ul class="arguments">
			<?php foreach($POST['ARGV'] as $argument => $value): ?>
				<li><strong><?=$argument?>:</strong> <code><?=escapeHTML($value)?></code></li>
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
