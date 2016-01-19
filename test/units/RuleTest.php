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
    public function testPatternSetAndMatch()
    {
        $object = new Rule();
        $object->disallow('/section*');
        $this->assertFalse($object->match('/section/tout/cetuqiadjoa.jpg'));

        $object->allow('/toto*.php$');
        $this->assertTrue($object->match('/toto/tata.PHP'));
        $this->assertTrue($object->match('/toto/tata.php'));

        $object->allow('/truite');
        $this->assertTrue($object->match('/truite/et/tout/cetuqiadjoa.jpg'));

        $object->disallow('/');
        $this->assertFalse($object->match('/to/tata.php'));
    }
}
