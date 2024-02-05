<?php
use ORM\EntityInterface;
use ORM\Entities\Category;
use ORM\Entities\Page;
use ORM\Entities\Post;
use ORM\Entities\User;

use Template\Template as Template;
use Template\Factory as TemplateFactory;

use Parsers\ArgumentParser;
use Parsers\FunctionParser;
use Parsers\EmoticonParser;
use Parsers\MarkdownParser;

#===============================================================================
# Create generic pagination template
#===============================================================================
function createPaginationTemplate($current, $last, string $location): Template {
	$params = http_build_query(array_merge($_GET, ['site' => '__SITE__']));
	$params = str_replace('%', '%%', $params);
	$params = str_replace('__SITE__', '%d', $params);

	$Pagination = TemplateFactory::build('pagination');
	$Pagination->set('THIS', $current);
	$Pagination->set('LAST', $last);
	$Pagination->set('HREF', "{$location}?{$params}");

	return $Pagination;
}

#===============================================================================
# Helper function to reduce duplicate code
#===============================================================================
function generateCategoryItemTemplate(Category $Category, bool $is_root = FALSE): Template {
	$CategoryRepository = Application::getRepository('Category');

	foreach($CategoryRepository->findWithParents($Category->getID()) as $Category) {
		$category_data = generateItemTemplateData($Category);
		$category_list[] = $category_data;
	}

	$Template = TemplateFactory::build('category/item');
	$Template->set('IS_ROOT', $is_root);
	$Template->set('CATEGORY', $category_data ?? []);
	$Template->set('CATEGORIES', $category_list ?? []);
	$Template->set('COUNT', [
		'POST' => $CategoryRepository->getNumberOfPosts($Category),
		'CHILDREN' => $CategoryRepository->getNumberOfChildren($Category)
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
	$ArgumentParser = new ArgumentParser;
	$FunctionParser = new FunctionParser;
	$MarkdownParser = new MarkdownParser;

	$attribute = $Entity->getAll(['password']);
	$attribute = array_change_key_case($attribute, CASE_UPPER);

	$text = $Entity->get('body');
	$text = $FunctionParser->transform($text);

	$arguments = $ArgumentParser->parse($Entity->get('argv') ?? '');

	$images = $MarkdownParser->parse($text)['img']['src'] ?? [];
	$images = array_map('htmlentities', $images);

	return [
		'URL' => Application::getEntityURL($Entity),
		'ARGV' => $arguments,
		'ATTR' => $attribute,

		'PREV' => FALSE,
		'NEXT' => FALSE,

		'FILE' => [
			'LIST' => $images,
		],

		'BODY' => [
			'TEXT' => function() use($text) {
				return $text;
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
# Parse entity content
#===============================================================================
function parseEntityContent(EntityInterface $Entity): string {
	$text = $Entity->get('body');

	$FunctionParser = new FunctionParser();
	$text = $FunctionParser->transform($text);

	if(Application::get('WRAP_EMOTICONS')) {
		$EmoticonParser = new EmoticonParser;
		$text = $EmoticonParser->transform($text);
	}

	$MarkdownParser = new MarkdownParser;
	return $MarkdownParser->transform($text);
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
# Get emoticons with their explanations
#===============================================================================
function getUnicodeEmoticons(): array {
	return (new EmoticonParser)->getEmoticons();
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

#===========================================================================
# Callback for (CATEGORY|PAGE|POST|USER) content function
#===========================================================================
function getEntityMarkdownLink($ns, $id, $text = NULL, $info = NULL): string {
	if(!$Entity = Application::getRepository($ns)->find($id)) {
		return sprintf('`{%s: *Reference error*}`', strtoupper($ns));
	}

	$title = $Entity->get('name') ?? $Entity->get('fullname');
	$href = Application::getEntityURL($Entity);
	$text = $text ?: "»{$title}«";

	if($info === NULL) {
		$info = sprintf('%s »%s«',
			Application::getLanguage()->text(strtolower($ns)),
			$title);
	}

	# Hotfix: Replace double quotes with single quotes because currently we
	# have no sane way to escape double quotes in a string intended to be
	# used as the title for a Markdown formatted link.
	$info = str_replace('"', "'", $info);

	return sprintf('[%s](%s "%s")',	$text, $href, $info);
}

#===========================================================================
# Callback for (CATEGORY|PAGE|POST|USER)_URL content function
#===========================================================================
function getEntityURL($ns, $id): string {
	if(!$Entity = Application::getRepository($ns)->find($id)) {
		return sprintf('`{%s_URL: *Reference error*}`', strtoupper($ns));
	}

	return Application::getEntityURL($Entity);
}

#===============================================================================
# Function for use in templates to get data of a category
#===============================================================================
function CATEGORY(int $id): array {
	$Repository = Application::getRepository('Category');

	if($Category = $Repository->find($id)) {
		return generateItemTemplateData($Category);
	}

	return [];
}

#===============================================================================
# Function for use in templates to get data of a page
#===============================================================================
function PAGE(int $id): array {
	$Repository = Application::getRepository('Page');

	if($Page = $Repository->find($id)) {
		return generateItemTemplateData($Page);
	}

	return [];
}

#===============================================================================
# Function for use in templates to get data of a post
#===============================================================================
function POST(int $id): array {
	$Repository = Application::getRepository('Post');

	if($Post = $Repository->find($id)) {
		return generateItemTemplateData($Post);
	}

	return [];
}

#===============================================================================
# Function for use in templates to get data of a user
#===============================================================================
function USER(int $id): array {
	$Repository = Application::getRepository('User');

	if($User = $Repository->find($id)) {
		return generateItemTemplateData($User);
	}

	return [];
}

#===========================================================================
# Get base URL (optionally extended by $extend)
#===========================================================================
FunctionParser::register('BASE_URL', function($extend = '') {
	return Application::getURL($extend);
});

#===========================================================================
# Get file URL (optionally extended by $extend)
#===========================================================================
FunctionParser::register('FILE_URL', function($extend = '') {
	return Application::getFileURL($extend);
});

#===========================================================================
# Get Markdown formatted *category* link
#===========================================================================
FunctionParser::register('CATEGORY', function($id, $text = NULL, $title = NULL) {
	return getEntityMarkdownLink('Category', $id, $text, $title);
});

#===========================================================================
# Get Markdown formatted *page* link
#===========================================================================
FunctionParser::register('PAGE', function($id, $text = NULL, $title = NULL) {
	return getEntityMarkdownLink('Page', $id, $text, $title);
});

#===========================================================================
# Get Markdown formatted *post* link
#===========================================================================
FunctionParser::register('POST', function($id, $text = NULL, $title = NULL) {
	return getEntityMarkdownLink('Post', $id, $text, $title);
});

#===========================================================================
# Get Markdown formatted *user* link
#===========================================================================
FunctionParser::register('USER', function($id, $text = NULL, $title = NULL) {
	return getEntityMarkdownLink('User', $id, $text, $title);
});

#===========================================================================
# Get URL to a category entity
#===========================================================================
FunctionParser::register('CATEGORY_URL', function($id) {
	return getEntityURL('Category', $id);
});

#===========================================================================
# Get URL to a page entity
#===========================================================================
FunctionParser::register('PAGE_URL', function($id) {
	return getEntityURL('Page', $id);
});

#===========================================================================
# Get URL to a post entity
#===========================================================================
FunctionParser::register('POST_URL', function($id) {
	return getEntityURL('Post', $id);
});

#===========================================================================
# Get URL to a user entity
#===========================================================================
FunctionParser::register('USER_URL', function($id) {
	return getEntityURL('User', $id);
});
