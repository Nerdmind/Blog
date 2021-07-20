<?php
namespace Parsers;
use ReflectionFunction;

class FunctionParser implements ParserInterface {
	private static $functions = [];

	#===============================================================================
	# Regular expressions
	#===============================================================================
	private const FUNCTION_SHELL_REGEX = '#\{\s?(%s)%s\s?\}#';
	private const ARGUMENT_PARTS_REGEX = '(?:\:( (?:(?:"[^"]*"|[0-9]+)(?:,[\s]*)?)+))?';
	private const ARGUMENT_SPLIT_REGEX = '#("[^"]*"|[0-9]+)(?:,[\s]*)?#';

	#===============================================================================
	# Register function
	#===============================================================================
	public static function register(string $name, callable $callback): void {
		$Function = new ReflectionFunction($callback);
		self::$functions[$name] = [
			'callback' => $callback,
			'required' => $Function->getNumberOfRequiredParameters()
		];
	}

	#===============================================================================
	# Parse functions
	#===============================================================================
	public function parse(string $text): array {
		$functionNames = array_keys(self::$functions);
		$functionNames = implode('|', $functionNames);

		$pattern = self::FUNCTION_SHELL_REGEX;
		$options = self::ARGUMENT_PARTS_REGEX;

		preg_match_all(sprintf($pattern, $functionNames, $options), $text, $matches);

		foreach(array_map(function($name, $parameters) {
			return [$name , $this->parseParameterString($parameters)];
		}, $matches[1], $matches[2]) as $match) {
			$functions[$match[0]][] = $match[1];
		}

		return $functions ?? [];
	}

	#===============================================================================
	# Transform functions
	#===============================================================================
	public function transform(string $text): string {
		$functionData = self::$functions;
		$functionNames = array_keys($functionData);
		$functionNames = implode('|', $functionNames);

		$pattern = self::FUNCTION_SHELL_REGEX;
		$options = self::ARGUMENT_PARTS_REGEX;

		return preg_replace_callback(sprintf($pattern, $functionNames, $options),
		function($matches) use($functionData) {
			$function = $matches[1];
			$callback = $functionData[$function]['callback'];
			$required = $functionData[$function]['required'];

			$arguments = $this->parseParameterString($matches[2] ?? '');

			if(count($arguments) < $required) {
				return sprintf('`{%s: *Missing arguments*}`', $function);
			}

			return $callback(...$arguments);
		}, $text);
	}

	#===============================================================================
	# Parse the parameter string found within the function shell
	#===============================================================================
	private function parseParameterString(string $parameters): array {
		preg_match_all(self::ARGUMENT_SPLIT_REGEX, $parameters, $matches);

		return array_map(function($argument) {
			return trim($argument, '"');
		}, $matches[1]);
	}
}
