<?php

namespace Bee4\RobotsTxt\Exception;

use InvalidArgumentException;

/**
 * Class InvalidContentException
 * Error thrown when the parser try to load an invalid content
 *
 * @copyright Bee4 2015
 * @author    Stephane HULARD <s.hulard@chstudio.fr>
 */
class InvalidContentException extends InvalidArgumentException
{
    /**
     * @var mixed
     */
    protected $content;

    /**
     * Failed content
     * @param mixed $content
     * @return InvalidContentException
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }
}
