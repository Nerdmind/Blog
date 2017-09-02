<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Page Main Template               [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

$user = "<a href=\"{$USER['URL']}\" title=\"alias »{$USER['ATTR']['USERNAME']}«\">{$USER['ATTR']['FULLNAME']}</a>";
$time = "<time datetime=\"{$PAGE['ATTR']['TIME_INSERT']}\" title=\"".parseDatetime($PAGE['ATTR']['TIME_INSERT'], '[W]')."\">".parseDatetime($PAGE['ATTR']['TIME_INSERT'], $Language->text('date_format'))."</time>";
?>
<h1><i class="fa fa-file-text-o"></i><?=escapeHTML($PAGE['ATTR']['NAME'])?></h1>
<p><?=$Language->text('page_main_heading_desc', [$user, $time])?></p>

<div id="content" class="page">
	<?=$PAGE['BODY']['HTML']()?>
</div>

<section id="site-navi">

	<?php if($PAGE['PREV']): ?>
		<div><a id="prev-site" href="<?=$PAGE['PREV']['URL']?>" title="<?=$Language->text('prev_page')?> »<?=escapeHTML($PAGE['PREV']['ATTR']['NAME'])?>«"><i class="fa fa-arrow-left"></i></a></div>
	<?php else: ?>
		<div><a class="disabled"><i class="fa fa-arrow-left"></i></a></div>
	<?php endif; ?>

	<?php if($PAGE['NEXT']): ?>
		<div><a id="next-site" href="<?=$PAGE['NEXT']['URL']?>" title="<?=$Language->text('next_page')?> »<?=escapeHTML($PAGE['NEXT']['ATTR']['NAME'])?>«"><i class="fa fa-arrow-right"></i></a></div>
	<?php else: ?>
		<div><a class="disabled"><i class="fa fa-arrow-right"></i></a></div>
	<?php endif; ?>

</section>