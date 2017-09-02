<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Post Main Template               [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

$user = "<a href=\"{$USER['URL']}\" title=\"alias »{$USER['ATTR']['USERNAME']}«\">{$USER['ATTR']['FULLNAME']}</a>";
$time = "<time datetime=\"{$POST['ATTR']['TIME_INSERT']}\" title=\"".parseDatetime($POST['ATTR']['TIME_INSERT'], '[W]')."\">".parseDatetime($POST['ATTR']['TIME_INSERT'], $Language->text('date_format'))."</time>";
?>
<h1><i class="fa fa-newspaper-o"></i><?=escapeHTML($POST['ATTR']['NAME'])?></h1>
<p><?=$Language->text('post_main_heading_desc', [$user, $time])?></p>

<div id="content" class="post">
	<?=$POST['BODY']['HTML']()?>
</div>

<section id="site-navi">

	<?php if($POST['PREV']): ?>
		<div><a id="prev-site" href="<?=$POST['PREV']['URL']?>" title="<?=$Language->text('prev_post')?> »<?=escapeHTML($POST['PREV']['ATTR']['NAME'])?>«"><i class="fa fa-arrow-left"></i></a></div>
	<?php else: ?>
		<div><a class="disabled"><i class="fa fa-arrow-left"></i></a></div>
	<?php endif; ?>

	<?php if($POST['NEXT']): ?>
		<div><a id="next-site" href="<?=$POST['NEXT']['URL']?>" title="<?=$Language->text('next_post')?> »<?=escapeHTML($POST['NEXT']['ATTR']['NAME'])?>«"><i class="fa fa-arrow-right"></i></a></div>
	<?php else: ?>
		<div><a class="disabled"><i class="fa fa-arrow-right"></i></a></div>
	<?php endif; ?>

</section>