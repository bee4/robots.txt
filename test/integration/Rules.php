<?php
/**
 * This file is part of the beebot package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Bee4 2016
 * @author    Stephane HULARD <s.hulard@chstudio.fr>
 */

namespace Bee4\RobotsTxt\Test\Integration;

use mageekguy\atoum;
use Bee4\RobotsTxt as LUT;

/**
 * Robots.txt integration test
 * @tags integration
 * @namespace  \Test\Integration
 */
class Rules extends atoum
{
    public function provide()
    {
        $data = [];
        $files = glob(__DIR__.'/samples/*-rules.ini');
        foreach ($files as $file) {
            $rules = parse_ini_file($file, true);
            $tmp = [
                file_get_contents(__DIR__.'/samples/'.basename($file, '-rules.ini').'.txt'),
                $rules
            ];
            $data[] = $tmp;
        }

        return $data;
    }

    /**
     * @dataProvider provide
     */
    public function testIntegration($content, array $rules)
    {
        $sut = LUT\Parser::parse($content);

        foreach ($rules as $rule) {
            $this
                ->when($matched = $sut->match($rule['ua'], $rule['url']))
                ->then
                    ->boolean($rule['mode'] === 'allow')
                        ->isEqualTo($matched);
        }
    }
}
