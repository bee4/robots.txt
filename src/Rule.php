<?php

namespace Bee4\RobotsTxt;

/**
 * Class Rule
 * Represent a Ruleset inside a Robots.txt file
 *
 * @package   Bee4\RobotsTxt
 * @license   http://opensource.org/licenses/Apache-2.0
 * @copyright Bee4 2015
 * @author	  Stephane HULARD <s.hulard@chstudio.fr>
 */
class Rule
{
	/**
	 * The regex patterns that identidy if the rule match or not!
	 * @var array
	 */
	protected $patterns = [
		'allow' => [],
		'disallow' => []
	];

	/**
	 * Add a pattern to match in the current rule by allowing
	 * @param string $pattern
	 * @return Rule
	 */
	public function allow($pattern) {
		$this->patterns['allow'][$pattern] = $this->handlePattern($pattern);
		return $this;
	}

	/**
	 * Add a pattern to match in the current rule by disallowing
	 * @param string $pattern
	 * @return Rule
	 */
	public function disallow($pattern) {
		$this->patterns['disallow'][$pattern] = $this->handlePattern($pattern);
		return $this;
	}

	/**
	 * Transform current pattern to be used for matching
	 * @param string $pattern
	 * @return string
	 */
	private function handlePattern($pattern) {
		$ended = substr($pattern, -1) === '$';
		$pattern = rtrim($pattern, '*');
		$pattern = rtrim($pattern, '$');

		$parts = explode('*', $pattern);
		array_walk($parts, function(&$part) {
			$part = preg_quote($part, '/');
		});
		return implode('.*', $parts).($ended?'':'.*');
	}

	/**
	 * Check if the URL is allowed or not
	 * @param string $url
	 * @return boolean
	 */
	public function match($url) {
		arsort($this->patterns['allow'], SORT_NUMERIC);
		arsort($this->patterns['disallow'], SORT_NUMERIC);

		$disallowed = implode('|', $this->patterns['disallow']);
		if( count($this->patterns['disallow']) > 0 &&
				preg_match('/^(?!('.$disallowed.')).*$/i', $url ) !== 1 ) {
			if( count($this->patterns['allow']) === 0 ) {
				return false;
			}

			$allowed = implode('|', $this->patterns['allow']);
			return preg_match('/^('.$allowed.')$/i', $url) === 1;
		}
		return true;
	}
}
