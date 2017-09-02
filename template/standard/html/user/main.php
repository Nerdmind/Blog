<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: User Main Template               [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<h1><i class="fa fa-user"></i><?=escapeHTML($USER['ATTR']['FULLNAME'])?></h1>
<p><em><?=$Language->text('user_main_heading_desc', [escapeHTML($USER['ATTR']['USERNAME']), $COUNT['POST'], $COUNT['PAGE']])?></em></p>

<div id="content" class="user">
	<?=$USER['BODY']['HTML']()?>
</div>

<section id="site-navi">

	<?php if($USER['PREV']): ?>
		<div><a id="prev-site" href="<?=$USER['PREV']['URL']?>" title="<?=$Language->text('prev_user')?> »<?=escapeHTML($USER['PREV']['ATTR']['FULLNAME'])?>«"><i class="fa fa-arrow-left"></i></a></div>
	<?php else: ?>
		<div><a class="disabled"><i class="fa fa-arrow-left"></i></a></div>
	<?php endif; ?>

	<?php if($USER['NEXT']): ?>
		<div><a id="next-site" href="<?=$USER['NEXT']['URL']?>" title="<?=$Language->text('next_user')?> »<?=escapeHTML($USER['NEXT']['ATTR']['FULLNAME'])?>«"><i class="fa fa-arrow-right"></i></a></div>
	<?php else: ?>
		<div><a class="disabled"><i class="fa fa-arrow-right"></i></a></div>
	<?php endif; ?>

</section>