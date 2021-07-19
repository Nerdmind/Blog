<?php
namespace Parsers;
use Application;

class EmoticonParser implements ParserInterface {
	private $Language;

	#===========================================================================
	# Initialize
	#===========================================================================
	public function __construct() {
		$this->Language = Application::getLanguage();
	}

	#===========================================================================
	# Get emoticons with their explanations
	#===========================================================================
	public function getEmoticons(): array {
		$Language = $this->Language;

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

	#===========================================================================
	# Parse occurring emoticons (*without* duplicates)
	#===========================================================================
	public function parse(string $text): array {
		$emoticon_data = $this->getEmoticons();
		$emoticon_list = array_keys($emoticon_data);
		$emoticon_list = implode('|', $emoticon_list);

		preg_match_all("#($emoticon_list)#", $text, $matches);

		foreach($matches[1] as $emoticon) {
			$emoticons[$emoticon] = $emoticon_data[$emoticon];
		}

		return $emoticons ?? [];
	}

	#===========================================================================
	# Wrap emoticons inside a titled span element
	#===========================================================================
	public function transform(string $text): string {
		$emoticon_data = $this->getEmoticons();
		$emoticon_list = array_keys($emoticon_data);
		$emoticon_list = implode('|', $emoticon_list);

		# TODO: Do not wrap emoticons if they occur inside a code block
		return preg_replace_callback("#($emoticon_list)#", function($matches)
		use($emoticon_data) {
			$emoticon = $matches[1];
			$explanation = $emoticon_data[$emoticon];
			return sprintf('<span title="%s">%s</span>', $explanation, $emoticon);
		}, $text);
	}
}
