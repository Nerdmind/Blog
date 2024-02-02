<?php
namespace Parsers;
use ReflectionFunction;

class FunctionParser implements ParserInterface {
	private static $functions = [];

	#===============================================================================
	# Main regex for matching the whole function call
	#===============================================================================
	private const FUNCTION_PATTERN = '/
	\{\s?                         # Opening curly brace `{`
		(?<func>%s)               # The function name in uppercase letters
		(?:\:\s                   # A colon `:` followed by a required blank
			(?<arg_list>          # Capture group for the whole argument list
				(?:%s)+           # One or more arguments (ARGUMENT_PATTERN_PARTIAL)
			)
		)?
	\s?\}                         # Closing curly brace `}`
	/x';

	#===============================================================================
	# Partial regex for matching/splitting the argument list
	# - Thanks to @OnlineCop from the #regex community of the Libera IRC network! <3
	#===============================================================================
	private const ARGUMENT_PATTERN_PARTIAL =
	'(?<arg>                      # Either a quoted string or a plain number
		(?<qmark>["\'])           # Either a single or double quote
		(?>[^"\'\\\\]++           # String between the quotes
		|    [\\\\].              # A `\` followed by anything but literal newline
		|    (?!\k<qmark>)["\']   # A quote, but not our opening quote
		)*+
		\k<qmark>                 # Closing quote (same as opening quote)
		|
		[0-9]+                    # ... or just a plain number
	)
	(?:,\s*)?';

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

		$pattern = self::FUNCTION_PATTERN;
		$options = self::ARGUMENT_PATTERN_PARTIAL;

		preg_match_all(sprintf($pattern, $functionNames, $options), $text, $matches);

		foreach(array_map(function($name, $parameters) {
			return [$name , $this->parseParameterString($parameters)];
		}, $matches['func'], $matches['arg_list']) as $match) {
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

		$pattern = self::FUNCTION_PATTERN;
		$options = self::ARGUMENT_PATTERN_PARTIAL;

		return preg_replace_callback(sprintf($pattern, $functionNames, $options),
		function($matches) use($functionData) {
			$function = $matches['func'];
			$callback = $functionData[$function]['callback'];
			$required = $functionData[$function]['required'];

			$arguments = $this->parseParameterString($matches['arg_list'] ?? '');

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
		$pattern = sprintf('/%s/x', self::ARGUMENT_PATTERN_PARTIAL);
		preg_match_all($pattern, $parameters, $matches);

		return array_map(function($arg, $qmark) {
			if(!$qmark) {
				return $arg;
			}

			# If a quotation mark is matched, the argument has been enclosed
			# between quotation marks when passed to the content function in
			# the editor. Therefore, the quotation marks must be removed and
			# we also need to take care of the backslash-escaped occurrences
			# of the quotation marks inside the argument string.

			$arg = substr($arg, 1);
			$arg = substr($arg, 0, strlen($arg)-1);
			$arg = str_replace('\\'.$qmark, $qmark, $arg);

			return $arg;
		}, $matches['arg'], $matches['qmark']);
	}
}
