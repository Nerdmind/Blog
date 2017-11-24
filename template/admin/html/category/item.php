<article class="item">
	<header>
		<h2><i class="fa fa-newspaper-o"></i><?=escapeHTML($CATEGORY['ATTR']['NAME'])?></h2>
		<div>
			<span class="brackets item-id">#<?=$CATEGORY['ATTR']['ID']?></span>
			<a class="brackets" href="<?=Application::getAdminURL("category/update.php?id={$CATEGORY['ATTR']['ID']}")?>" title="<?=$Language->text('update_category')?>"><?=escapeHTML($CATEGORY['ATTR']['NAME'])?></a>
			<time class="brackets" datetime="<?=$CATEGORY['ATTR']['TIME_INSERT']?>"><?=parseDatetime($CATEGORY['ATTR']['TIME_INSERT'], $Language->text('date_format'))?></time>
		</div>
	</header>
	<blockquote cite="<?=$CATEGORY['URL']?>">
        <?php foreach($CATEGORY['ATTR'] as $argument => $value): ?>
            <p><strong><?=$argument?>:</strong> <span><?=escapeHTML($value)?></span></p>
        <?php endforeach; ?>
	</blockquote>
	<footer>
		<ul>
			<li><a href="<?=$CATEGORY['URL']?>" target="_blank" title="<?=$Language->text('select_category')?>"><i class="fa fa-external-link"></i><span class="hidden"><?=$Language->text('select_category')?></span></a></li>
			<li><a href="<?=Application::getAdminURL("category/update.php?id={$CATEGORY['ATTR']['ID']}")?>" title="<?=$Language->text('update_category')?>"><i class="fa fa-pencil-square-o"></i><span class="hidden"><?=$Language->text('update_category')?></span></a></li>
			<li><a href="<?=Application::getAdminURL("category/delete.php?id={$CATEGORY['ATTR']['ID']}")?>" title="<?=$Language->text('delete_category')?>"><i class="fa fa-trash-o"></i><span class="hidden"><?=$Language->text('update_category')?></span></a></li>
		</ul>
	</footer>
</article>