<?php

namespace Bee4\RobotsTxt\Exception;

use Exception;
use Bee4\RobotsTxt\Rule;

/**
 * Class DuplicateRuleException
 * Error thrown when the parser try to add 2 rules for the same UA
 *
 * @copyright Bee4 2015
 * @author    Stephane HULARD <s.hulard@chstudio.fr>
 */
class InvalidContentException extends \InvalidArgumentException
{
    /**
     * @var Rule
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
