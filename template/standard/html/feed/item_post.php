<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Feed Item Template [post]        [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<item>
	<title><?=escapeHTML($POST['ATTR']['NAME'])?></title>
	<link><?=$POST['URL']?></link>
	<guid isPermaLink="false"><?=$POST['GUID']?></guid>
	<pubDate><?=parseDatetime($POST['ATTR']['TIME_INSERT'], '[RFC2822]')?></pubDate>
	<dc:creator><?=escapeHTML($USER['ATTR']['FULLNAME'])?></dc:creator>
	<description><?=escapeHTML(cut(removeLineBreaksAndTabs(removeHTML($POST['BODY']['HTML'])), 400))?></description>
	<content:encoded>
		<![CDATA[
			<?=$POST['BODY']['HTML']?>
			<p><small><strong>Kommentare:</strong> [<a href="https://keybase.io/nerdmind">0x33EB32A2</a>] blog&#64;nerdmind.de</small></p>
		]]>
	</content:encoded>
	<?php foreach($POST['FILE']['LIST'] as $fileURL): ?>
		<media:content url="<?=$fileURL?>" medium="image"></media:content>
	<?php endforeach; ?>
</item>