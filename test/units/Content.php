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
use Bee4\RobotsTxt\Content as SUT;

/**
 * Content unit test
 */
class Content extends atoum
{
    const CONTENT = 'A sample content';

    public function testGet()
    {
        $this
            ->given($sut = new SUT(self::CONTENT))
            ->then
                ->string($sut->get())
                    ->isEqualTo(self::CONTENT);
    }

    public function testWithBom()
    {
        $this
            ->given($sut = new SUT(SUT::UTF8_BOM.self::CONTENT))
            ->then
                ->string($sut->get())
                    ->isEqualTo(self::CONTENT);
    }
}
