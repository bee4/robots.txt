<?php

namespace Bee4\RobotsTxt\Exception;

use InvalidArgumentException;

/**
 * Class InvalidUrlException
 * Error thrown when a sitemap is added to a Rules but is an invalid URL
 *
 * @copyright Bee4 2015
 * @author    Stephane HULARD <s.hulard@chstudio.fr>
 */
class InvalidUrlException extends InvalidArgumentException
{
    /**
     * @var mixed
     */
    protected $url;

    /**
     * Failed url
     * @param mixed $url
     * @return InvalidUrlException
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }
}
