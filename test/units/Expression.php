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
use Bee4\RobotsTxt\Expression as SUT;

/**
 * Expression unit test
 */
class Expression extends atoum
{
    public function patternDataProvider()
    {
        return [
            ['/page-1.html', '\/page\-1\.html.*'],
            ['/page-1.html$', '\/page\-1\.html'],
            ['/page*/dossier.html', '\/page.*\/dossier\.html.*'],
            ['/page*/dossier.html$', '\/page.*\/dossier\.html'],
            ['/page+1$', '\/page\+1'],
            ['/page+1?query=toto$', '\/page\+1\?query\=toto'],
            ['/page+1?query[0]=foo&query[1]=bar$', '\/page\+1\?query\[0\]\=foo&query\[1\]\=bar'],
        ];
    }

    /**
     * @dataProvider patternDataProvider
     */
    public function testPattern($exp, $pattern)
    {
        $this
            ->given($sut = new SUT($exp))
            ->then
                ->string($sut->getRaw())
                    ->isEqualTo($exp)
                ->castToString($sut)
                    ->isEqualTo($pattern);
    }

    public function containsDataProvider()
    {
        return [
            ['/page-1', '/page-1/dossier.html', true],
            ['/page-1$', '/page-1/dossier.html', false],
            ['/page*', '/page-1/dossier.html', true],
            ['/page*', '/page*/dossier.html', true],
        ];
    }

    /**
     * @dataProvider containsDataProvider
     */
    public function testContains($exp, $exp2, $contains)
    {
        $this
            ->given(
                $sut = new SUT($exp),
                $sut2 = new SUT($exp2)
            )
            ->then
                ->boolean($sut->contains($sut2))
                    ->isEqualTo($contains);
    }

    /**
     * @dataProvider containsDataProvider
     */
    public function testContained($exp, $exp2, $contained)
    {
        $this
            ->given(
                $sut = new SUT($exp),
                $sut2 = new SUT($exp2)
            )
            ->then
                ->boolean($sut2->contained($sut))
                    ->isEqualTo($contained);
    }
}
