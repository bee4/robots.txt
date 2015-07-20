<?php
/**
 * This file is part of the beebot package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Bee4 2015
 * @author    Stephane HULARD <s.hulard@chstudio.fr>
 * @package   Test\Bee4\RobotsTxt
 */

namespace Test\Bee4\RobotsTxt;

use Bee4\RobotsTxt\Parser;
use Bee4\RobotsTxt\Rules;
use Bee4\RobotsTxt\ParserFactory;

/**
 * Parser unit test
 * @package Test\Bee4\RobotsTxt
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{
	protected $content = "User-agent: *
Disallow: /mentions-legales/

User-agent: google-bot
Allow: /truite.php
disallow: /";

	protected $duplicateRuleContent = "User-agent: *
Disallow: /mentions-legales/

User-agent: *
Allow: /truite.php";


	public function testParse() {
		$object = new Parser($this->content);
		$rules = $object->parse();

		$rule = $rules->get('*');
		$this->assertInstanceOf('\Bee4\RobotsTxt\Rule', $rule);

		$this->assertFalse($rule->match('/mentions-legales/'));
		$this->assertTrue($rule->match('/another-page.html'));

		$this->assertFalse($rules->match('Google-Bot v01', '/toto'));
		$this->assertTrue($rules->match('Google-Bot v01', '/truite.php'));
	}

	public function testEmptyContentParse() {
		$object = new Parser("");
		$rules = $object->parse();

		$rule = $rules->get(Rules::DEFAULT_UA);
		$this->assertInstanceOf('\Bee4\RobotsTxt\Rule', $rule);
		$this->assertTrue($rule->match('/another-page.html'));
	}


	/**
	 * @expectedException Bee4\RobotsTxt\Exception\DuplicateRuleException
	 */
	public function testDuplicateRuleParse() {
		$object = new Parser($this->duplicateRuleContent);
		$object->parse();
	}

	public function testParserFactory() {
		$rules = ParserFactory::build("http://www.bee4.fr");
		$this->assertInstanceOf('\Bee4\RobotsTxt\Rule', $rules->get('*'));
	}
}
