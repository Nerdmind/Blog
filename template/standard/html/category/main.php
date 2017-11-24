<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Category Main Template           [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
$time = "<time datetime=\"{$CATEGORY['ATTR']['TIME_INSERT']}\" title=\"".parseDatetime($CATEGORY['ATTR']['TIME_INSERT'], '[W]')."\">".parseDatetime($CATEGORY['ATTR']['TIME_INSERT'], $Language->text('date_format'))."</time>";
?>
<h1><i class="fa fa-newspaper-o"></i><?=escapeHTML($CATEGORY['ATTR']['NAME'])?></h1>
<p><?=$Language->text('category_main_heading_desc', $time)?></p>

<div id="content" class="category">
    <?php foreach($LIST['POSTS'] as $post): ?>
        <?php if (!$post->parameters['POST']['ATTR']['ARCHIVE']) : ?>
            <?php echo $post; ?>
        <? endif; ?>
    <?php endforeach; ?>
</div>

<section id="site-navi">
	<?php if($CATEGORY['PREV']): ?>
		<div><a id="prev-site" href="<?=$CATEGORY['PREV']['URL']?>" title="<?=$Language->text('prev_category')?> »<?=escapeHTML($CATEGORY['PREV']['ATTR']['NAME'])?>«"><i class="fa fa-arrow-left"></i></a></div>
	<?php else: ?>
		<div><a class="disabled"><i class="fa fa-arrow-left"></i></a></div>
	<?php endif; ?>

	<?php if($CATEGORY['NEXT']): ?>
		<div><a id="next-site" href="<?=$CATEGORY['NEXT']['URL']?>" title="<?=$Language->text('next_category')?> »<?=escapeHTML($CATEGORY['NEXT']['ATTR']['NAME'])?>«"><i class="fa fa-arrow-right"></i></a></div>
	<?php else: ?>
		<div><a class="disabled"><i class="fa fa-arrow-right"></i></a></div>
	<?php endif; ?>
</section>