<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Feed Item Template [post]        [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

$HTML = $POST['BODY']['HTML']();
?>
<item>
	<title><?=escapeHTML($POST['ATTR']['NAME'])?></title>
	<link><?=$POST['URL']?></link>
	<guid isPermaLink="false"><?=$POST['GUID']?></guid>
	<pubDate><?=parseDatetime($POST['ATTR']['TIME_INSERT'], '[RFC2822]')?></pubDate>
	<dc:creator><?=escapeHTML($USER['ATTR']['FULLNAME'])?></dc:creator>
	<description><?=escapeHTML(description($HTML, 400))?></description>
	<content:encoded>
		<![CDATA[
			<?=$HTML?>
		]]>
	</content:encoded>
	<?php foreach($POST['FILE']['LIST'] as $fileURL): ?>
		<media:content url="<?=$fileURL?>" medium="image"></media:content>
	<?php endforeach; ?>
</item>