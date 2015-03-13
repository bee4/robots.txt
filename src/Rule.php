<?php
/**
 * This file is part of the beebot package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Bee4 2015
 * @author	Stephane HULARD <s.hulard@chstudio.fr>
 * @package Bee4\RobotsTxt
 */

namespace Bee4\RobotsTxt;

/**
 * Class Rule
 * Represent a Ruleset inside a Robots.txt file
 * @package Bee4\RobotsTxt
 */
class Rule
{
	/**
	 * The UserAgent corresponding to the rule
	 * @var string
	 */
	protected $ua;

	/**
	 * The regex pattern that identidy if the rule match or not!
	 * @var string
	 */
	protected $pattern;

	public function __construct($ua) {
		$this->ua = $ua;
		$this->pattern = '';
	}

	/**
	 * Add a pattern to match in the current rule
	 * @param string $pattern
	 * @return Rule
	 */
	public function addPattern($pattern) {
		$ended = substr($pattern, -1) === '$';
		$pattern = rtrim($pattern, '*');
		$pattern = rtrim($pattern, '$');

		$parts = explode('*', $pattern);
		array_walk($parts, function(&$part) {
			$part = preg_quote($part, '/');
		});

		if( $this->pattern != '' ) {
			$this->pattern .= '|';
		}
		$this->pattern .= implode('.*', $parts).($ended?'':'.*');

		return $this;
	}

	/**
	 * Check if the URL is allowed or not
	 * @param string $url
	 * @return boolean
	 */
	public function match($url) {
		return preg_match('/^'.$this->pattern.'$/i', $url) != false;
	}
}
