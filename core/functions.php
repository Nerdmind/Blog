<?php
use ORM\EntityInterface;
use ORM\Entities\Category;
use ORM\Entities\Page;
use ORM\Entities\Post;
use ORM\Entities\User;

use Template\Template as Template;
use Template\Factory as TemplateFactory;

#===============================================================================
# Create generic pagination template
#===============================================================================
function createPaginationTemplate($current, $last, string $location): Template {
	$Pagination = TemplateFactory::build('pagination');
	$Pagination->set('THIS', $current);
	$Pagination->set('LAST', $last);
	$Pagination->set('HREF', "{$location}?site=%d");

	return $Pagination;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generateCategoryItemTemplate(Category $Category, bool $is_root = FALSE): Template {
	$CategoryRepository = Application::getRepository('Category');
	$PostRepository = Application::getRepository('Post');

	foreach($CategoryRepository->findWithParents($Category->getID()) as $Category) {
		$category_data = generateItemTemplateData($Category);
		$category_list[] = $category_data;
	}

	$Template = TemplateFactory::build('category/item');
	$Template->set('IS_ROOT', $is_root);
	$Template->set('CATEGORY', $category_data ?? []);
	$Template->set('CATEGORIES', $category_list ?? []);
	$Template->set('COUNT', [
		'POST' => $PostRepository->getCountByCategory($Category),
		'CHILDREN' => $CategoryRepository->getChildrenCount($Category)
	]);

	return $Template;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generatePageItemTemplate(Page $Page, User $User): Template {
	$Template = TemplateFactory::build('page/item');
	$Template->set('PAGE', generateItemTemplateData($Page));
	$Template->set('USER', generateItemTemplateData($User));

	return $Template;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generatePostItemTemplate(Post $Post, User $User): Template {
	$CategoryRepository = Application::getRepository('Category');

	foreach($CategoryRepository->findWithParents($Post->get('category')) as $Category) {
		$category_data = generateItemTemplateData($Category);
		$categories[] = $category_data;
	}

	$Template = TemplateFactory::build('post/item');
	$Template->set('POST', generateItemTemplateData($Post));
	$Template->set('USER', generateItemTemplateData($User));
	$Template->set('CATEGORY', $category_data ?? []);
	$Template->set('CATEGORIES', $categories ?? []);

	return $Template;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generateUserItemTemplate(User $User): Template {
	$Template = TemplateFactory::build('user/item');
	$Template->set('USER', generateItemTemplateData($User));

	return $Template;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generateItemTemplateData(EntityInterface $Entity): array {
	$ATTR = $Entity->getAll(['password']);
	$ATTR = array_change_key_case($ATTR, CASE_UPPER);

	$preparsed = parseContentTags($Entity->get('body'));

	return [
		'URL' => Application::getEntityURL($Entity),
		'GUID' => generatePseudoGUID($Entity),
		'ARGV' => parseArguments($Entity->get('argv')),

		'ATTR' => $ATTR,

		'PREV' => FALSE,
		'NEXT' => FALSE,

		'FILE' => [
			'LIST' => getMarkdownImageURLs($preparsed),
		],

		'BODY' => [
			'TEXT' => function() use($preparsed) {
				return $preparsed;
			},
			'HTML' => function() use($Entity) {
				return parseEntityContent($Entity);
			}
		]
	];
}

#===============================================================================
# Generate a nested tree from a category data array
#===============================================================================
function generateCategoryDataTree(array $category_data, $root = 0): array {
	foreach($category_data as &$category){
		$tree[intval($category['PARENT'])][] = &$category;
		unset($category['PARENT']);
	}

	foreach($category_data as &$category){
		if (isset($tree[$category['ID']])){
			$category['CHILDS'] = $tree[$category['ID']];
		}
	}

	return $tree[$root] ?? [];
}

#===============================================================================
# Generate pseudo GUID for entity
#===============================================================================
function generatePseudoGUID(EntityInterface $Entity) {
	switch(get_class($Entity)) {
		case "ORM\Entities\Post":
			$attr = Application::get('POST.FEED_GUID');
			break;
		default:
			$attr = ['id', 'time_insert'];
	}

	foreach($attr as $attribute) {
		$attributes[] = $Entity->get($attribute);
	}

	return sha1(implode('', $attributes));
}

#===============================================================================
# Parse content tags
#===============================================================================
function parseContentTags(string $text): string {
	$entity_tags = '#\{(POST|PAGE|USER)\[([0-9]+)\]\}#';

	$text = preg_replace_callback($entity_tags, function($matches) {
		$namespace = ucfirst(strtolower($matches[1]));
		$Repository = Application::getRepository($namespace);

		if($Entity = $Repository->find($matches[2])) {
			return Application::getEntityURL($Entity);
		}

		else {
			return '{undefined}';
		}
	}, $text);

	$base_tag = '#\{BASE\[\"([^"]+)\"\]\}#';
	$file_tag = '#\{FILE\[\"([^"]+)\"\]\}#';

	$text = preg_replace($base_tag, \Application::getURL('$1'), $text);
	$text = preg_replace($file_tag, \Application::getFileURL('$1'), $text);

	return $text;
}

#===============================================================================
# Parse entity content
#===============================================================================
function parseEntityContent(EntityInterface $Entity): string {
	switch($class = get_class($Entity)) {
		case 'ORM\Entities\Category':
			$prefix = 'CATEGORY';
			break;
		case 'ORM\Entities\Page':
			$prefix = 'PAGE';
			break;
		case 'ORM\Entities\Post':
			$prefix = 'POST';
			break;
		case 'ORM\Entities\User':
			$prefix = 'USER';
			break;
		default:
			$error = 'Unknown config prefix for <code>%s</code> entities.';
			throw new Exception(sprintf($error, $class));
	}

	$Parsedown = new Parsedown();
	$Parsedown->setUrlsLinked(FALSE);

	$text = parseContentTags($Entity->get('body'));

	if(Application::get("$prefix.EMOTICONS")) {
		$text = parseUnicodeEmoticons($text);
		$text = parseEmoticons($text);
	}

	return $Parsedown->text($text);
}

#===============================================================================
# Extract Markdown formatted image URLs
#===============================================================================
function getMarkdownImageURLs(string $text): array {
	$pattern = '#\!\[(.*)\][ ]?(?:\n[ ]*)?\((.*)(\s[\'"](.*)[\'"])?\)#U';
	$content = parseContentTags($text);

	if(preg_match_all($pattern, $content, $matches)) {
		return array_map('htmlentities', $matches[2]);
	}

	return [];
}

#===============================================================================
# Parse argument string to array
#===============================================================================
function parseArguments(?string $argv): array {
	if($argv) {
		foreach(explode('|', $argv) as $delimeter) {
			$part = explode('=', $delimeter);

			$argumentK = $part[0] ?? NULL;
			$argumentV = $part[1] ?? TRUE;

			if(preg_match('#^[[:word:]]+$#', $argumentK)) {
				$arguments[strtoupper($argumentK)] = $argumentV;
			}
		}
	}

	return $arguments ?? [];
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
	$string = strip_tags($string);
	$string = removeDoubleLineBreaks($string);
	$string = truncate($string, $length, $replace);
	$string = nl2br($string);

	return $string;
}

#===============================================================================
# Return content for meta description
#===============================================================================
function description($string, $length = 200, $replace = ' […]') {
	$string = strip_tags($string);
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
	$Repository = Application::getRepository('Page');

	if($Page = $Repository->find($id)) {
		return generateItemTemplateData($Page);
	}

	return [];
}

#===============================================================================
# Function to get data from specific post in templates
#===============================================================================
function POST(int $id): array {
	$Repository = Application::getRepository('Post');

	if($Post = $Repository->find($id)) {
		return generateItemTemplateData($Post);
	}

	return [];
}

#===============================================================================
# Function to get data from specific user in templates
#===============================================================================
function USER(int $id): array {
	$Repository = Application::getRepository('User');

	if($User = $Repository->find($id)) {
		return generateItemTemplateData($User);
	}

	return [];
}
