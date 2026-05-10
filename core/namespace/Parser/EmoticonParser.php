<?php
namespace Parser;
use Application;

class EmoticonParser implements ParserInterface {
	private $language;

	#===========================================================================
	# Initialize
	#===========================================================================
	public function __construct() {
		$this->language = Application::getLanguage();
	}

	#===========================================================================
	# Get emoticons with their explanations
	#===========================================================================
	public function getEmoticons(): array {
		$language = $this->language;

		return [
			html_entity_decode('&#x1F60A;') => $language->text('emoticon_1F60A'),
			html_entity_decode('&#x1F61E;') => $language->text('emoticon_1F61E'),
			html_entity_decode('&#x1F603;') => $language->text('emoticon_1F603'),
			html_entity_decode('&#x1F61B;') => $language->text('emoticon_1F61B'),
			html_entity_decode('&#x1F632;') => $language->text('emoticon_1F632'),
			html_entity_decode('&#x1F609;') => $language->text('emoticon_1F609'),
			html_entity_decode('&#x1F622;') => $language->text('emoticon_1F622'),
			html_entity_decode('&#x1F610;') => $language->text('emoticon_1F610'),
			html_entity_decode('&#x1F635;') => $language->text('emoticon_1F635'),
			html_entity_decode('&#x1F612;') => $language->text('emoticon_1F612'),
			html_entity_decode('&#x1F60E;') => $language->text('emoticon_1F60E'),
			html_entity_decode('&#x1F61F;') => $language->text('emoticon_1F61F'),
			html_entity_decode('&#x1F602;') => $language->text('emoticon_1F602'),
			html_entity_decode('&#x1F604;') => $language->text('emoticon_1F604'),
		];
	}

	#===========================================================================
	# Parse occurring emoticons (*without* duplicates)
	#===========================================================================
	public function parse(string $text): array {
		$emoticonData = $this->getEmoticons();
		$emoticonList = array_keys($emoticonData);
		$emoticonList = implode('|', $emoticonList);

		preg_match_all("#($emoticonList)#", $text, $matches);

		foreach($matches[1] as $emoticon) {
			$emoticons[$emoticon] = $emoticonData[$emoticon];
		}

		return $emoticons ?? [];
	}

	#===========================================================================
	# Wrap emoticons inside a titled span element
	#===========================================================================
	public function transform(string $text): string {
		$emoticonData = $this->getEmoticons();
		$emoticonList = array_keys($emoticonData);
		$emoticonList = implode('|', $emoticonList);

		# TODO: Do not wrap emoticons if they occur inside a code block
		return preg_replace_callback("#($emoticonList)#", function($matches)
		use($emoticonData) {
			$emoticon = $matches[1];
			$explanation = $emoticonData[$emoticon];
			return sprintf('<span title="%s">%s</span>', $explanation, $emoticon);
		}, $text);
	}
}
