<?php
#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generateNaviTemplate(int $current, $location, $namespace): Template\Template {
	$Database = Application::getDatabase();
	$Attribute = "{$namespace}\\Attribute";

	$Statement = $Database->query(sprintf('SELECT COUNT(id) FROM %s', $Attribute::TABLE));

	$lastSite = ceil($Statement->fetchColumn() / Application::get(strtoupper($namespace).'.LIST_SIZE'));

	$PaginationTemplate = Template\Factory::build('pagination');
	$PaginationTemplate->set('THIS', $current);
	$PaginationTemplate->set('LAST', $lastSite);
	$PaginationTemplate->set('HREF', "{$location}?site=%d");

	return $PaginationTemplate;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generatePageNaviTemplate($current): Template\Template {
	return generateNaviTemplate($current, Application::getPageURL(), 'Page');
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generatePostNaviTemplate($current): Template\Template {
	return generateNaviTemplate($current, Application::getPostURL(), 'Post');
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generateUserNaviTemplate($current): Template\Template {
	return generateNaviTemplate($current, Application::getUserURL(), 'User');
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generatePageItemTemplate(Page\Item $Page, User\Item $User): Template\Template {
	$Template = Template\Factory::build('page/item');
	$Template->set('PAGE', generateItemTemplateData($Page));
	$Template->set('USER', generateItemTemplateData($User));

	return $Template;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generatePostItemTemplate(Post\Item $Post, User\Item $User): Template\Template {
	$Template = Template\Factory::build('post/item');
	$Template->set('POST', generateItemTemplateData($Post));
	$Template->set('USER', generateItemTemplateData($User));

	return $Template;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generateUserItemTemplate(User\Item $User): Template\Template {
	$Template = Template\Factory::build('user/item');
	$Template->set('USER', generateItemTemplateData($User));

	return $Template;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generateItemTemplateData(Item $Item): array {
	$ATTR = $Item->getAttribute()->getAll(['password']);
	$ATTR = array_change_key_case($ATTR, CASE_UPPER);

	return [
		'URL' => $Item->getURL(),
		'GUID' => $Item->getGUID(),
		'ARGV' => $Item->getArguments(),

		'ATTR' => $ATTR,

		'PREV' => FALSE,
		'NEXT' => FALSE,

		'FILE' => [
			'LIST' => $Item->getFiles()
		],

		'BODY' => [
			'TEXT' => function() use($Item) {
				return $Item->getBody();
			},
			'HTML' => function() use($Item) {
				return $Item->getHTML();
			}
		]
	];
}

#===============================================================================
# Parser for datetime formatted strings [YYYY-MM-DD HH:II:SS]
#===============================================================================
function parseDatetime($datetime, $format): string {
	list($date, $time) = explode(' ', $datetime);

	list($DATE['Y'], $DATE['M'], $DATE['D']) = explode('-', $date);
	list($TIME['H'], $TIME['M'], $TIME['S']) = explode(':', $time);

	$unixtime = strtotime($datetime);

	return strtr($format, [
		'[Y]' => $DATE['Y'],
		'[M]' => $DATE['M'],
		'[D]' => $DATE['D'],
		'[H]' => $TIME['H'],
		'[I]' => $TIME['M'],
		'[S]' => $TIME['S'],
		'[W]' => strftime('%A', $unixtime),
		'[F]' => strftime('%B', $unixtime),
		'[DATE]' => $date,
		'[TIME]' => $time,
		'[RFC2822]' => date('r', $unixtime)
	]);
}

#===============================================================================
# Get emoticons with unicode characters and description
#===============================================================================
function getEmoticons(): array {
	$Language = Application::getLanguage();

	return [
		':)' => ['&#x1F60A;', $Language->text('emoticon_1F60A')],
		':(' => ['&#x1F61E;', $Language->text('emoticon_1F61E')],
		':D' => ['&#x1F603;', $Language->text('emoticon_1F603')],
		':P' => ['&#x1F61B;', $Language->text('emoticon_1F61B')],
		':O' => ['&#x1F632;', $Language->text('emoticon_1F632')],
		';)' => ['&#x1F609;', $Language->text('emoticon_1F609')],
		';(' => ['&#x1F622;', $Language->text('emoticon_1F622')],
		':|' => ['&#x1F610;', $Language->text('emoticon_1F610')],
		':X' => ['&#x1F635;', $Language->text('emoticon_1F635')],
		':/' => ['&#x1F612;', $Language->text('emoticon_1F612')],
		'8)' => ['&#x1F60E;', $Language->text('emoticon_1F60E')],
		':S' => ['&#x1F61F;', $Language->text('emoticon_1F61F')],
		'xD' => ['&#x1F602;', $Language->text('emoticon_1F602')],
		'^^' => ['&#x1F604;', $Language->text('emoticon_1F604')],
	];
}

#===============================================================================
# Parse emoticons to HTML encoded unicode characters
#===============================================================================
function parseEmoticons($string): string {
	foreach(getEmoticons() as $emoticon => $data) {
		$pattern = '#(^|\s)'.preg_quote($emoticon).'#';
		$replace = "\\1<span title=\"{$data[1]}\">{$data[0]}</span>";

		$string = preg_replace($pattern, $replace, $string);
	}

	return $string;
}

#===============================================================================
# Get unicode emoticons with their corresponding explanation
#===============================================================================
function getUnicodeEmoticons(): array {
	$Language = Application::getLanguage();

	return [
		html_entity_decode('&#x1F60A;') => $Language->text('emoticon_1F60A'),
		html_entity_decode('&#x1F61E;') => $Language->text('emoticon_1F61E'),
		html_entity_decode('&#x1F603;') => $Language->text('emoticon_1F603'),
		html_entity_decode('&#x1F61B;') => $Language->text('emoticon_1F61B'),
		html_entity_decode('&#x1F632;') => $Language->text('emoticon_1F632'),
		html_entity_decode('&#x1F609;') => $Language->text('emoticon_1F609'),
		html_entity_decode('&#x1F622;') => $Language->text('emoticon_1F622'),
		html_entity_decode('&#x1F610;') => $Language->text('emoticon_1F610'),
		html_entity_decode('&#x1F635;') => $Language->text('emoticon_1F635'),
		html_entity_decode('&#x1F612;') => $Language->text('emoticon_1F612'),
		html_entity_decode('&#x1F60E;') => $Language->text('emoticon_1F60E'),
		html_entity_decode('&#x1F61F;') => $Language->text('emoticon_1F61F'),
		html_entity_decode('&#x1F602;') => $Language->text('emoticon_1F602'),
		html_entity_decode('&#x1F604;') => $Language->text('emoticon_1F604'),
	];
}

#===============================================================================
# Wrap emoticons in <span> element with "title" attribute for explanation
#===============================================================================
function parseUnicodeEmoticons($string): string {
	foreach(getUnicodeEmoticons() as $emoticon => $explanation) {
		$pattern = '#(^|\s)'.preg_quote($emoticon).'#';
		$replace = "\\1<span title=\"{$explanation}\">{$emoticon}</span>";

		$string = preg_replace($pattern, $replace, $string);
	}

	return $string;
}

#===============================================================================
# Wrapper function for htmlspecialchars()
#===============================================================================
function escapeHTML($string): string {
	return htmlspecialchars($string);
}

#===============================================================================
# Wrapper function for strip_tags()
#===============================================================================
function removeHTML($string): string {
	return strip_tags($string);
}

#===============================================================================
# Remove all double line breaks from string
#===============================================================================
function removeDoubleLineBreaks($string): string {
	return preg_replace('#(\r?\n){2,}#', "\n\n", $string);
}

#===============================================================================
# Remove all multiple whitespace characters
#===============================================================================
function removeWhitespace($string): string {
	return preg_replace('/\s+/S', ' ', trim($string));
}

#===============================================================================
# Return truncated string
#===============================================================================
function truncate($string, $length, $replace = '') {
	if(mb_strlen($string) > $length) {
		$truncated = preg_replace("/^(.{0,{$length}}\\b).*/su", '$1', $string);
		$truncated = trim($truncated);

		# The additional trim call is useful, because if $truncated is empty,
		# then there will be an unnecessary space between those two variables
		# if $replace is preceded by a space (for example: " […]").
		return trim("{$truncated}{$replace}");
	}

	return $string;
}

#===============================================================================
# Return excerpt content
#===============================================================================
function excerpt($string, $length = 500, $replace = ' […]') {
	$string = removeHTML($string);
	$string = removeDoubleLineBreaks($string);
	$string = truncate($string, $length, $replace);
	$string = nl2br($string);

	return $string;
}

#===============================================================================
# Return content for meta description
#===============================================================================
function description($string, $length = 200, $replace = ' […]') {
	$string = removeHTML($string);
	$string = removeWhitespace($string);
	$string = truncate($string, $length, $replace);

	return $string;
}

#===============================================================================
# Generate a valid slug URL part from a string
#===============================================================================
function generateSlug($string, $separator = '-') {
	$string = strtr(mb_strtolower($string), [
		'ä' => 'ae',
		'ö' => 'oe',
		'ü' => 'ue',
		'ß' => 'ss'
	]);

	$string = preg_replace('#[^[:lower:][:digit:]]+#', $separator, $string);

	return trim($string, $separator);
}

#===============================================================================
# Function to get data from specific page in templates
#===============================================================================
function PAGE(int $id): array {
	try {
		$Page = Page\Factory::build($id);
		return generateItemTemplateData($Page);
	} catch(Page\Exception $Exception) {
		return [];
	}
}

#===============================================================================
# Function to get data from specific post in templates
#===============================================================================
function POST(int $id): array {
	try {
		$Post = Post\Factory::build($id);
		return generateItemTemplateData($Post);
	} catch(Post\Exception $Exception) {
		return [];
	}
}

#===============================================================================
# Function to get data from specific user in templates
#===============================================================================
function USER(int $id): array {
	try {
		$User = User\Factory::build($id);
		return generateItemTemplateData($User);
	} catch(User\Exception $Exception) {
		return [];
	}
}
