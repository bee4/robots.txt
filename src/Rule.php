<?php

namespace Bee4\RobotsTxt;

/**
 * Class Rule
 * Represent a Ruleset inside a Robots.txt file
 *
 * @copyright Bee4 2015
 * @author    Stephane HULARD <s.hulard@chstudio.fr>
 */
class Rule
{
    const COMPILED = 'compiled';
    const DIRTY    = 'dirty';

    /**
     * User agent on which the rule apply
     * @var string
     */
    private $ua;

    /**
     * Rule status (compiled or dirty)
     * @var string
     */
    private $state;

    /**
     * Expression collection with allow / disallow segments
     * @var array
     */
    private $exp = [
        'allow'    => [],
        'disallow' => []
    ];

    /**
     * Compiled regex pattern with allow / disallow segments
     * @var array
     */
    private $patterns = [
        'allow'    => '',
        'disallow' => ''
    ];

    /**
     * @param string $ua
     */
    public function __construct($ua)
    {
        $this->ua = $ua;
    }

    /**
     * Retrieve rule's UserAgent
     * @return string
     */
    public function getUserAgent()
    {
        return $this->ua;
    }

    /**
     * Add a pattern to match in the current rule by allowing
     * @param string $pattern
     * @return Rule
     */
    public function allow($pattern)
    {
        return $this->addExpression(new Expression($pattern), 'allow');
    }

    /**
     * Add a pattern to match in the current rule by disallowing
     * @param string $pattern
     * @return Rule
     */
    public function disallow($pattern)
    {
        return $this->addExpression(new Expression($pattern), 'disallow');
    }

    /**
     * Add an expression in the current rule
     * @param string $pattern Expression raw pattern
     * @param string $mode    Expression mode (allow / disallow)
     * @return Expression
     */
    private function addExpression(Expression $exp, $mode)
    {
        $this->state = self::DIRTY;
        $this->exp[$mode][] = $exp;
        return $this;
    }

    /**
     * Compile expressions to a global pattern
     * @return boolean
     */
    private function compile()
    {
        if (self::COMPILED === $this->state) {
            return true;
        }

        $process = function (array &$patterns) {
            usort($patterns, function (Expression $a, Expression $b) {
                return strlen($a->getRaw()) < strlen($b->getRaw());
            });

            return '/^(('.implode(')|(', $patterns).'))$/';
        };
        $this->patterns['allow'] = $process($this->exp['allow']);
        $this->patterns['disallow'] = $process($this->exp['disallow']);
        $this->state = self::COMPILED;
    }

    /**
     * Check if the URL is allowed or not
     * @param string $url
     * @return boolean
     */
    public function match($url)
    {
        $this->compile();

        if (0 < count($this->exp['disallow']) &&
            1 === preg_match($this->patterns['disallow'], $url, $disallowed) ) {
            if (0 < count($this->exp['allow']) &&
                1 === preg_match($this->patterns['allow'], $url, $allowed)
            ) {
                $a = $this->lastFilledIndex($allowed);
                $d = $this->lastFilledIndex($disallowed);
                return
                    strlen($this->exp['allow'][$a-2]->getRaw()) >=
                        strlen($this->exp['disallow'][$d-2]->getRaw());
            }

            return false;
        }

        return true;
    }

    /**
     * Retrieve the last filled index in a given array
     * @param  array  $data
     * @return integer
     */
    private function lastFilledIndex(array $data)
    {
        return key(array_slice(array_filter($data), -1, 1, true));
    }
}
