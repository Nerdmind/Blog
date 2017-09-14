<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: User Item Template               [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<article class="item">
	<header>
		<h2>
			<a title="<?=$Language->text('select_user')?>: »<?=escapeHTML($USER['ATTR']['FULLNAME'])?>«" href="<?=$USER['URL']?>"><?=escapeHTML($USER['ATTR']['FULLNAME'])?></a>
		</h2>
		<span class="brackets info"><?=escapeHTML($USER['ATTR']['USERNAME'])?></span>
	</header>
	<blockquote cite="<?=$USER['URL']?>">
		<?=$USER['BODY']['HTML']()?>
	</blockquote>
</article>