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
 * Class ParserFactory
 * Take an URL, try to load the robots.txt file and return the parsed rules
 * @package Bee4\RobotsTxt
 */
class ParserFactory
{
	public static function build($item) {
		if( filter_var($item, FILTER_VALIDATE_URL)!==false ) {
			$parsed = parse_url($item);
			if( isset($parsed['path']) && $parsed['path'] != '/robots.txt' ) {
				throw new \InvalidArgumentException('The robots.txt file can\'t be found at: '.$item.' this file must be hosted at website root');
			}

			$parsed['path'] = '/robots.txt';
			$parsed = array_intersect_key($parsed, array_flip(['scheme', 'host', 'port', 'path']));
			$url = $parsed['scheme'].'://'.$parsed['host'].(isset($parsed['port'])?':'.$parsed['port']:'').$parsed['path'];

			$handle = curl_init();
			curl_setopt($handle, CURLOPT_URL, $url);
			curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
			$item = curl_exec($handle);
			$status = curl_getinfo($handle, CURLINFO_HTTP_CODE);
			curl_close($handle);

			if( $status !== 200 ) {
				throw new \RuntimeException('Can\'t access the robots.txt file at: '.$url);
			}
		}

		$parser = new Parser($item);
		return $parser->parse();
	}
}