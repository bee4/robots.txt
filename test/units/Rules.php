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
use Bee4\RobotsTxt\Rules as SUT;
use Bee4\RobotsTxt as LUT;

/**
 * Rules unit test
 */
class Rules extends atoum
{
    public function testDefaultUa()
    {
        $this
            ->given(
                $sut = new SUT(),
                $ua = SUT::DEFAULT_UA
            )
            ->when($rule = $sut->get('Invalid UA'))
            ->then
                ->integer(count($sut))
                    ->isEqualTo(1)
                ->object($rule)
                    ->isInstanceOf('Bee4\RobotsTxt\Rule')
                ->string($rule->getUserAgent())
                    ->isEqualTo($ua)
                ->boolean($sut->match($ua, '/url'))
                    ->isTrue();
    }

    public function testAddDefaultRule()
    {
        $this
            ->given(
                $sut = new SUT(),
                $ua = SUT::DEFAULT_UA,
                $rule = new LUT\Rule($ua)
            )
            ->when(
                $sut->add($rule),
                $get = $sut->get($ua)
            )
            ->then
                ->integer(count($sut))
                    ->isEqualTo(1)
                ->object($get)
                    ->isInstanceOf('Bee4\RobotsTxt\Rule')
                    ->isEqualTo($rule)
                ->boolean($sut->match($ua, '/url'))
                    ->isTrue();
    }

    public function testAddRule()
    {
        $this
            ->given(
                $sut = new SUT(),
                $ua = 'Google-Bot',
                $rule = new LUT\Rule($ua),
                $rule->allow('/url'),
                $rule->disallow('/private')
            )
            ->when(
                $sut->add($rule),
                $get = $sut->get($ua)
            )
            ->then
                ->integer(count($sut))
                    ->isEqualTo(2)
                ->object($get)
                    ->isInstanceOf('Bee4\RobotsTxt\Rule')
                    ->isEqualTo($rule)
                ->boolean($sut->match($ua, '/url'))
                    ->isTrue()
                ->boolean($sut->match($ua, '/private'))
                    ->isFalse();
    }

    public function testDuplicateRuleException()
    {
        $this
            ->given(
                $sut = new SUT(),
                $ua = 'Google-Bot',
                $rule = new LUT\Rule($ua),
                $sut->add($rule)
            )
            ->exception(function() use ($sut, $rule) {
                $sut->add($rule);
            })
                ->isInstanceOf('Bee4\RobotsTxt\Exception\DuplicateRuleException')
            ->then
                ->object($this->exception->getRule())
                    ->isEqualTo($rule);
    }
}
