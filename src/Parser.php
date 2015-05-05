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
 * Class Parser
 * Take the content of a robots.txt file and transform it to rules
 * @package Bee4\RobotsTxt
 */
class Parser
{
	/**
	 * Robots.txt file content
	 * @var string
	 */
	protected $content;

	public function __construct($content) {
		//Remove the UTF8 BOM
		$this->content = trim($content, "\xEF\xBB\xBF");
	}

	public function parse() {
		$rules = new Rules();
		$ua = $rule = null;
		$separator = "\r\n";
		$line = strtok($this->content, $separator);
		while ($line !== false) {
			if( strpos($line, '#') !== 0 ) {
				if( preg_match('/^User-Agent\: (.*)$/i', $line, $matches)) {
					if( $ua !== null ) {
						$rules->add($ua, $rule);
					}
					$ua = $matches[1];
					$rule = new Rule();
				} elseif( preg_match('/^Allow: (.*)$/i', $line, $matches)) {
					$rule->allow($matches[1]);
				} elseif( preg_match('/^Disallow: (.*)$/i', $line, $matches)) {
					$rule->disallow($matches[1]);
				}
			}

			$line = strtok( $separator );
		}
		//Handle the last item in the loop
		if( $rule instanceof Rule ) {
			$rules->add($ua, $rule);
		}

		return $rules;
	}
}
