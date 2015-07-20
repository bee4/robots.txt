<?php
/**
 * This file is part of the beebot package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Bee4 2015
 * @author	Stephane HULARD <s.hulard@chstudio.fr>
 * @package Bee4\RobotsTxt\Exception
 */

namespace Bee4\RobotsTxt\Exception;

use Exception;
use Bee4\RobotsTxt\Rule;

/**
 * Class DuplicateRuleException
 * Error thrown when the parser try to add 2 rules for the same UA
 * @package Bee4\RobotsTxt\Exception
 */
class DuplicateRuleException extends Exception
{
	/**
	 * @var Rule
	 */
	protected $rule;

	/**
	 * @var string
	 */
	protected $ua;

	/**
	 * Rule setter
	 * @param Rule $rule
	 * @return DuplicateRuleException
	 */
	public function setRule(Rule $rule) {
		$this->rule = $rule;
		return $this;
	}
	/**
	 * @return Rule
	 */
	public function getRule() {
		return $this->rule;
	}

	/**
	 * User Agent setter
	 * @param string $ua
	 * @return DuplicateRuleException
	 */
	public function setUserAgent($ua) {
		$this->ua = $ua;
		return $this;
	}
	/**
	 * @return string
	 */
	public function getUserAgent() {
		return $this->ua;
	}
}
