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
use Bee4\RobotsTxt\Rule as SUT;

/**
 * Rule unit test
 */
class Rule extends atoum
{
    public function testAllowAllAsDefault()
    {
        $this
            ->given($sut = new SUT(''))
            ->when($match = $sut->match('/some/url'))
            ->then
                ->boolean($match)
                    ->isTrue();
    }

    public function testAllow()
    {
        $this
            ->given(
                $sut = new SUT(''),
                $sut->allow('/foo/bar')
            )
            ->when($match = $sut->match('/foo/bar'))
            ->then
                ->boolean($match)
                    ->isTrue();
    }

    public function testDisallow()
    {
        $this
            ->given(
                $sut = new SUT(''),
                $sut->disallow('/foo/bar'),
                $sut->allow('/foo/bar/baz$')
            )
            ->when($match = $sut->match('/foo/bar'))
            ->then
                ->boolean($match)
                    ->isFalse()
            ->when($match = $sut->match('/foo/bar/baz/'))
            ->then
                ->boolean($match)
                    ->isFalse()
            ->when($match = $sut->match('/foo/bar/baz'))
            ->then
                ->boolean($match)
                    ->isTrue();
    }

    public function testUserAgent()
    {
        $this
            ->given($sut = new SUT('UA'))
            ->then
                ->string($sut->getUserAgent())
                    ->isEqualTo('UA');
    }

    public function testMultipleExpression()
    {
        $this
            ->given(
                $sut = new SUT(''),
                $sut->disallow('/foo/bar'),
                $sut->allow('/foo/bar/baz$'),
                $sut->allow('/foo$')
            )
            ->when($match = $sut->match('/foo/bar'))
            ->then
                ->boolean($match)
                    ->isFalse()
            ->when($match = $sut->match('/foo/bar/baz/'))
            ->then
                ->boolean($match)
                    ->isFalse()
            ->when($match = $sut->match('/foo/bar/baz'))
            ->then
                ->boolean($match)
                    ->isTrue();
    }
}
