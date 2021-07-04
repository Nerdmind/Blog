<article class="item">
	<header>
		<h2>
			<i class="fa fa-tag"></i><?=escapeHTML($CATEGORY['ATTR']['NAME'])?>
			<span class="item-id">#<?=$CATEGORY['ATTR']['ID']?></span>
		</h2>
		<ul class="item-meta">
			<li>
				<time class="brackets" datetime="<?=$CATEGORY['ATTR']['TIME_INSERT']?>"><?=parseDatetime($CATEGORY['ATTR']['TIME_INSERT'], $Language->text('date_format'))?></time>
			</li>
			<li>
				<span class="brackets"><?=$Language->text('posts')?>: <?=$COUNT['POST']?></span>
			</li>
			<li>
				<span class="brackets"><?=$Language->text('categories')?>: <?=$COUNT['CHILDREN']?></span>
			</li>
		</ul>
	</header>
	<blockquote cite="<?=$CATEGORY['URL']?>">
		<?php if(isset($CATEGORY['FILE']['LIST'][0])): ?>
			<img class="item-image" src="<?=$CATEGORY['FILE']['LIST'][0]?>" alt="" />
		<?php endif; ?>
		<p><?=excerpt($CATEGORY['BODY']['HTML']())?></p>
	</blockquote>

	<?php if($CATEGORY['ARGV']): ?>
		<ul class="arguments">
			<?php foreach($CATEGORY['ARGV'] as $argument => $value): ?>
				<li><strong><?=$argument?>:</strong> <span><?=escapeHTML($value)?></span></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<footer>
		<ul>
			<li><a href="<?=$CATEGORY['URL']?>" target="_blank" title="<?=$Language->text('select_category')?>"><i class="fa fa-external-link"></i><span class="hidden"><?=$Language->text('select_category')?></span></a></li>
			<li><a href="<?=Application::getAdminURL("category/update.php?id={$CATEGORY['ATTR']['ID']}")?>" title="<?=$Language->text('update_category')?>"><i class="fa fa-pencil-square-o"></i><span class="hidden"><?=$Language->text('update_category')?></span></a></li>
			<li><a href="<?=Application::getAdminURL("category/delete.php?id={$CATEGORY['ATTR']['ID']}")?>" title="<?=$Language->text('delete_category')?>"><i class="fa fa-trash-o"></i><span class="hidden"><?=$Language->text('delete_category')?></span></a></li>
		</ul>
	</footer>
</article>
