<?php
/**
 * This file is part of the beebot package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Bee4 2016
 * @author    Stephane HULARD <s.hulard@chstudio.fr>
 */

namespace Bee4\RobotsTxt\Test\Units;

use mageekguy\atoum;
use Bee4\RobotsTxt\Parser as SUT;
use Bee4\RobotsTxt as LUT;

/**
 * Parser unit test
 */
class Parser extends atoum
{
    public function testEmptyStringContent()
    {
        $this
            ->given($rules = SUT::parse(""))
            ->when($rule = $rules->get(LUT\Rules::DEFAULT_UA))
            ->then
                ->object($rules)
                    ->isInstanceOf('Bee4\RobotsTxt\Rules')
                ->object($rule)
                    ->isInstanceOf('Bee4\RobotsTxt\Rule');
    }

    public function testEmptyContentInstance()
    {
        $this
            ->given(
                $content = new LUT\Content(""),
                $rules = SUT::parse($content)
            )
            ->when($rule = $rules->get(LUT\Rules::DEFAULT_UA))
            ->then
                ->object($rules)
                    ->isInstanceOf('Bee4\RobotsTxt\Rules')
                ->object($rule)
                    ->isInstanceOf('Bee4\RobotsTxt\Rule');
    }

    public function testInvalidContentException()
    {
        $this->exception(function () {
            SUT::parse(new \stdClass);
        })
            ->isInstanceOf('Bee4\RobotsTxt\Exception\InvalidContentException');
    }

    public function testDuplicateRuleException()
    {
        $robot = <<<ROBOTS
User-Agent: *
Allow: /

User-Agent: *
Disallow: /toto
ROBOTS;

        $this
            ->given($content = new LUT\Content($robot))
            ->exception(function () use ($content) {
                SUT::parse($content);
            })
                ->isInstanceOf('Bee4\RobotsTxt\Exception\DuplicateRuleException');
    }

    public function testParse()
    {
        $robot = <<<ROBOTS
User-Agent: *
Allow: /

User-Agent: Google
Disallow: /toto

#Sitemap
Sitemap: http://localhost
ROBOTS;

        $this
            ->given($rules = SUT::parse($robot))
            ->then
                ->sizeOf($rules)
                    ->isEqualTo(2)
                ->boolean($rules->match('*', '/page'))
                    ->isTrue()
                ->boolean($rules->match('Google', '/page'))
                    ->isTrue()
                ->boolean($rules->match('Google', '/toto/page'))
                    ->isFalse()
                ->array($rules->getSitemaps())
                    ->hasSize(1)
                    ->contains('http://localhost');
    }
}
