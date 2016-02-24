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
     * Parse the current content
     * @return Rules
     */
    public function analyze($content)
    {
        $content = $this->handleContent($content);

        $rules = new Rules();
        $current = [];
        $ua = false;

        foreach ($content as $line) {
            if (0 === strpos($line, '#')) {
                continue;
            }

            if (preg_match('/^\s*User-Agent\:(.*)$/i', $line, $matches)) {
                if ($ua !== true) {
                    $this->populateRules($rules, $current);
                    $current = [];
                }
                $current[] = new Rule(trim($matches[1]));
                $ua = true;
            } else {
                $ua = false;
                $this->parseLine($current, $line, $rules);
            }
        }
        $this->populateRules($rules, $current);
        return $rules;
    }

    /**
     * Handle content to build a valid instance
     * @param  string|Content $content
     * @return Content
     */
    private function handleContent($content)
    {
        if (is_string($content)) {
            $content = new Content($content);
        }
        if (!($content instanceof Content)) {
            throw (new InvalidContentException(
                'Content must be a `string` or a `Content` instance'
            ))->setContent($content);
        }

        return $content;
    }

    /**
     * Transform file content to structured Rules
     * @param string|Content $content
     * @return Rules
     */
    public static function parse($content)
    {
        $parser = new self();
        return $parser->analyze($content);
    }

    /**
     * Parse a line of data
     * @param  array  &$current
     * @param  string $line
     */
    private function parseLine(array &$current, $line, Rules $rules)
    {
        if (preg_match('/^\s*(Allow|Disallow):[ ]*((\*).+|(\/.*))$/i', $line, $matches)) {
            $match = array_values(
                array_filter(
                    array_slice($matches, 3)
                )
            );

            $this->apply(
                $current,
                strtolower($matches[1]),
                trim($match[0])
            );
        } elseif (preg_match('/^\s*Sitemap:(.*)$/i', $line, $matches)) {
            $rules->addSitemap(trim($matches[1]));
        }
    }

    /**
     * Apply a method on all element of a given array
     * @param  array  $data
     * @param  string $method
     * @param  string $param
     */
    private function apply(array $data, $method, $param)
    {
        array_walk($data, function (Rule $item) use ($method, $param) {
            $item->$method($param);
        });
    }

    /**
     * Populate rules property with build Rule instance
     * @param  Rules  $rules
     * @param  array  $current Collection of Rule objects
     */
    private function populateRules(Rules $rules, array $current)
    {
        foreach ($current as $item) {
            $rules->add($item);
        }
    }
}
