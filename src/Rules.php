<?php

namespace Bee4\RobotsTxt;

use Bee4\RobotsTxt\Exception\DuplicateRuleException;

/**
 * Class Rules
 * Represent a collection of Rules
 *
 * @package   Bee4\RobotsTxt
 * @license   http://opensource.org/licenses/Apache-2.0
 * @copyright Bee4 2015
 * @author	  Stephane HULARD <s.hulard@chstudio.fr>
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
	 * @param string $userAgent
	 * @param Rule $rule
	 * @return Rules
	 */
	public function add($userAgent, Rule $rule) {
		$userAgent = $this->handleUa($userAgent);
		if( isset($this->collection[$userAgent]) &&
				$this->collection[$userAgent] !== $this->defaultRule ) {
			throw (new DuplicateRuleException(
				'You can\'t add 2 rules for the same UserAgent'
			))->setRule($rule)
				->setUserAgent($userAgent);
		}
		$this->collection[$userAgent] = $rule;

		return $this;
	}

	/**
	 * Check if the URL match for the given UA or not
	 * @param string $userAgent
	 * @param string $url
	 * @return boolean
	 */
	public function match($userAgent, $url) {
		if( ($rule = $this->get($userAgent)) === null ) {
			return false;
		}
		return $rule->match($url);
	}

	/**
	 * Retrieve rules for a given UA
	 * @param string $userAgent
	 * @return null|Rule
	 */
	public function get($userAgent) {
		$item = null;
		$iterator = new \ArrayIterator($this->collection);
		iterator_apply($iterator,
			function(\ArrayIterator $iterator, $userAgent) use (&$item) {
				if( $iterator->key() != Rules::DEFAULT_UA &&
						preg_match($iterator->key(), $userAgent) === 1 ) {
					$item = $iterator->current();
					return false;
				}
				return true;
			},
			[$iterator, $userAgent]
		);

		return $item!==null?
			$item:
			(isset($this->collection[self::DEFAULT_UA])?
				$this->collection[self::DEFAULT_UA]:
				null);
	}

	/**
	 * Update the UA to make a valid regexp
	 * @param string $userAgent
	 * @return string
	 */
	private function handleUa($userAgent) {
		if( $userAgent == self::DEFAULT_UA ) {
			return $userAgent;
		}
		return '/^'.preg_quote($userAgent).'.*/i';
	}
}
