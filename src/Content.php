<?php

namespace Bee4\RobotsTxt;

/**
 * Class Parser
 * Take the content of a robots.txt file and transform it to rules
 *
 * @copyright Bee4 2015
 * @author    Stephane HULARD <s.hulard@chstudio.fr>
 */
class Content
{
    const UTF8_BOM = "\xEF\xBB\xBF";

    /**
     * Robots.txt file content
     * @var string
     */
    protected $content;

    /**
     * @param string $content
     */
    public function __construct($content)
    {
        //Remove the UTF8 BOM
        $this->content = trim($content, self::UTF8_BOM);
    }

    /**
     * Content accessor
     * @return string
     */
    public function get()
    {
        return $this->content;
    }
}
