<?php

namespace Bee4\RobotsTxt;

/**
 * Class Parser
 * Take the content of a robots.txt file and transform it to rules
 *
 * @package Bee4\RobotsTxt
 * @license   http://opensource.org/licenses/Apache-2.0
 * @copyright Bee4 2015
 * @author      Stephane HULARD <s.hulard@chstudio.fr>
 */
class Parser
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
        $this->content = trim($content, Parser::UTF8_BOM);
    }

    /**
     * Content accessor
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Transform file content to structured Rules
     * @return Rules The valid ruleset
     */
    public function parse()
    {
        $rules = new Rules();
        $userAgent = $rule = null;
        $separator = "\r\n";
        $line = strtok($this->content, $separator);
        while ($line !== false) {
            if (strpos($line, '#') !== 0) {
                if (preg_match('/^User-Agent\: (.*)$/i', $line, $matches)) {
                    if ($userAgent !== null && $rule !== null) {
                        $rules->add($userAgent, $rule);
                    }
                    $userAgent = $matches[1];
                    $rule = new Rule();
                } elseif (preg_match('/^Allow: (.*)$/i', $line, $matches)) {
                    $rule->allow($matches[1]);
                } elseif (preg_match('/^Disallow: (.*)$/i', $line, $matches)) {
                    $rule->disallow($matches[1]);
                }
            }

            $line = strtok($separator);
        }
        //Handle the last item in the loop
        if ($rule instanceof Rule) {
            $rules->add($userAgent, $rule);
        }

        return $rules;
    }
}
