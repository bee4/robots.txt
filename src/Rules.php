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
 * Class Rules
 * Represent a collection of Rules
 * @package Bee4\RobotsTxt
 */
class Rules
{
	const DEFAULT_UA = '*';

	/**
	 * The collection of rules
	 * @var array
	 */
	protected $collection = [];

	private $defaultRule;

	public function __construct() {
		$this->defaultRule = new Rule();
		$this->add(self::DEFAULT_UA, $this->defaultRule);
	}

	/**
	 * Add a new rule to the collection
	 * @param string $ua
	 * @param Rule $rule
	 * @return Rules
	 */
	public function add($ua, Rule $rule) {
		$ua = $this->handleUa($ua);
		if( isset($this->collection[$ua]) && $this->collection[$ua] !== $this->defaultRule ) {
			throw new \RuntimeException('You can\'t add 2 rules for the same UserAgent');
		}
		$this->collection[$ua] = $rule;

		return $this;
	}

	/**
	 * Check if the URL match for the given UA or not
	 * @param string $ua
	 * @param string $url
	 * @return boolean
	 */
	public function match($ua, $url) {
		if( ($rule = $this->get($ua)) === null ) {
			return false;
		}
		return $rule->match($url);
	}

	/**
	 * Retrieve rules for a given UA
	 * @param string $ua
	 * @return null|Rule
	 */
	public function get($ua) {
		$item = null;
		$it = new \ArrayIterator($this->collection);
		iterator_apply($it, function($it, $ua) use (&$item) {
			if( $it->key() != Rules::DEFAULT_UA && preg_match($it->key(), $ua) != false ) {
				$item = $it->current();
				return false;
			}
			return true;
		}, [$it, $ua]);

		return $item!==null?$item:(isset($this->collection[self::DEFAULT_UA])?$this->collection[self::DEFAULT_UA]:null);
	}

	/**
	 * Update the UA to make a valid regexp
	 * @param string $ua
	 * @return string
	 */
	private function handleUa($ua) {
		if( $ua == self::DEFAULT_UA ) {
			return $ua;
		}
		return '/^'.preg_quote($ua).'.*/i';
	}
}