<?php

namespace Bee4\RobotsTxt;

use Bee4\RobotsTxt\Exception\InvalidUrlException;
use RuntimeException;

/**
 * Class ContentFactory
 * Take an URL, try to load the robots.txt file and return content
 *
 * @copyright Bee4 2015
 * @author    Stephane HULARD <s.hulard@chstudio.fr>
 */
class ContentFactory
{
    /**
     * Build a parser instance from a string
     * @param  string $item     Can be an URL or a file content
     * @return Content          The built instance
     */
    public static function build($item)
    {
        if (filter_var($item, FILTER_VALIDATE_URL)!==false) {
            $parsed = parse_url($item);
            if (isset($parsed['path']) && $parsed['path'] != '/robots.txt') {
                throw (new InvalidUrlException(
                    sprintf(
                        'The robots.txt file can\'t be found at: %s',
                        $item
                    )
                ))->setUrl($item);
            }

            $parsed['path'] = '/robots.txt';
            $parsed = array_intersect_key(
                $parsed,
                array_flip(['scheme', 'host', 'port', 'path'])
            );
            $port = isset($parsed['port'])?':'.$parsed['port']:'';
            $url = $parsed['scheme'].'://'.$parsed['host'].$port.$parsed['path'];

            $item = self::download($url);
        } elseif (is_file($item) && is_readable($item)) {
            if (false === ($item = file_get_contents($item))) {
                throw new \RuntimeException(sprintf(
                    "File can't be read: %s",
                    $item
                ));
            }
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Content can\'t be built from given item: %s',
                $item
            ));
        }

        return new Content($item);
    }

    /**
     * Extract the content at URL
     * @param  string $url The robots.txt URL
     * @return string      The robots file content
     */
    protected static function download($url)
    {
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $item = curl_exec($handle);
        $status = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        if ($status !== 200) {
            throw new RuntimeException(sprintf(
                'Can\'t access the robots.txt file at: %s',
                $url
            ));
        }

        return $item;
    }
}
