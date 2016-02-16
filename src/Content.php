<?php

namespace Bee4\RobotsTxt;

/**
 * Class Content
 * Represent the content of a robots.txt file
 * It can be crawled as an Iterator
 *
 * @copyright Bee4 2015
 * @author    Stephane HULARD <s.hulard@chstudio.fr>
 */
class Content implements \Iterator
{
    const UTF8_BOM = "\xEF\xBB\xBF";

    /**
     * Robots.txt file content
     * @var string
     */
    protected $content;

    /**
     * Reader separator
     * @var string
     */
    protected $separator;

    /**
     * Current line
     * @var string
     */
    private $line;

    /**
     * Current iterator key
     * @var integer
     */
    private $read = 0;

    /**
     * @param string $content
     */
    public function __construct($content, $separator = "\r\n")
    {
        //Remove the UTF8 BOM
        $this->content = trim($content, self::UTF8_BOM);
        $this->separator = $separator;
    }

    /**
     * Retrieve current line
     * @return string
     */
    public function current()
    {
        return $this->line;
    }

    /**
     * Number of chars read
     * @return integer
     */
    public function key()
    {
        return $this->read;
    }

    /**
     * Get the next line in content
     */
    public function next()
    {
        if ($this->line !== null) {
            $this->line = strtok($this->separator);
        } else {
            $this->line = strtok($this->content, $this->separator);
        }
        $this->read += strlen($this->line);
    }

    /**
     * Rewind at beginning
     */
    public function rewind()
    {
        $this->line = null;
        $this->read = 0;
    }

    /**
     * Check if current item is valid or not
     * @return boolean
     */
    public function valid()
    {
        return $this->line !== false;
    }
}
