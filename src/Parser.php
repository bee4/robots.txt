<?php

namespace Bee4\RobotsTxt;

use Bee4\RobotsTxt\Exception\InvalidContentException;

/**
 * Class Parser
 * Take the content of a robots.txt file and transform it to rules
 *
 * @copyright Bee4 2015
 * @author    Stephane HULARD <s.hulard@chstudio.fr>
 */
class Parser
{
    /**
     * Transform file content to structured Rules
     * @param string|Content $content
     * @return Rules
     */
    public static function parse($content)
    {
        if (is_string($content)) {
            $content = new Content($content);
        }
        if (!($content instanceof Content)) {
            throw (new InvalidContentException(
                'Content must be a `string` or a `Content` instance'
            ))->setContent($content);
        }

        $rules = new Rules();
        $userAgent = $rule = null;
        $separator = "\r\n";
        $line = strtok($content->get(), $separator);
        while ($line !== false) {
            if (strpos($line, '#') !== 0) {
                if (preg_match('/^\s*User-Agent\: (.*)$/i', $line, $matches)) {
                    if ($userAgent !== null && $rule !== null) {
                        $rules->add($rule);
                    }
                    $userAgent = $matches[1];
                    $rule = new Rule($userAgent);
                } elseif (preg_match('/^\s*Allow: (.*)$/i', $line, $matches)) {
                    $rule->allow($matches[1]);
                } elseif (preg_match('/^\s*Disallow: (.*)$/i', $line, $matches)) {
                    $rule->disallow($matches[1]);
                }
            }

            $line = strtok($separator);
        }
        //Handle the last item in the loop
        if ($rule instanceof Rule) {
            $rules->add($rule);
        }

        return $rules;
    }
}
