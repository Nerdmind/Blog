<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: User Item Template               [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<li class="item-list-li user">
	<header>
		<h2>
			<?=escapeHTML($USER['ATTR']['FULLNAME'])."\n"?>
			<a class="brackets info" href="<?=$USER['URL']?>"><?=escapeHTML($USER['ATTR']['USERNAME'])?></a>
		</h2>
	</header>
	<article>
		<?=$USER['BODY']['HTML']?>
	</article>
</li>