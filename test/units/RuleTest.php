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

use Bee4\RobotsTxt\Rule;

/**
 * Rule unit test
 * @package Test\Bee4\RobotsTxt
 */
class RuleTest extends \PHPUnit_Framework_TestCase
{
	public function testPatternSetAndMatch() {
		$object = new Rule('Bot1');
		$object->addPattern('/toto*.php$');
		$object->addPattern('/truite');
		$object->addPattern('/section*');

		$this->assertTrue($object->match('/toto/tata.php'));
		$this->assertTrue($object->match('/toto/tata.PHP'));
		$this->assertTrue($object->match('/truite/et/tout/cetuqiadjoa.jpg'));
		$this->assertTrue($object->match('/section/tout/cetuqiadjoa.jpg'));
		$this->assertFalse($object->match('/to/tata.php'));
	}
}
