<?php

namespace Bee4\RobotsTxt;

/**
 * Class Expression
 * Represent a matching expression rule
 *
 * @copyright Bee4 2016
 * @author    Stephane HULARD <s.hulard@chstudio.fr>
 */
class Expression
{
    /**
     * Raw definition
     * @var string
     */
    private $raw;

    /**
     * Rule pattern
     * @var string
     */
    private $pattern;

    /**
     * Initialize expression
     * @param string $rule
     */
    public function __construct($rule, $operator = self::ALLOW)
    {
        $this->raw = $rule;
    }

    /**
     * Retrieve the raw rule definition
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * Transform current pattern to be used for matching
     * @param string $raw
     * @return string
     */
    private function build()
    {
        $raw = $this->raw;

        $ended = substr($raw, -1) === '$';
        $raw = rtrim($raw, '*');
        $raw = rtrim($raw, '$');

        $parts = explode('*', $raw);
        array_walk($parts, function (&$part) {
            $part = preg_quote($part, '/');
        });
        return implode('.*', $parts).($ended?'':'.*');
    }

    /**
     * Check if current expression is contained in another
     * @param  Expression $exp
     * @return boolean
     */
    public function contained(Expression $exp)
    {
        return $exp->contains($this);
    }

    /**
     * Check if current expression contains another
     * @param  Expression $exp
     * @return boolean
     */
    public function contains(Expression $exp)
    {
        return preg_match('/^'.(string)$this.'$/', $exp->getRaw()) === 1;
    }

    /**
     * Retrieve the regex pattern corresponding to the Expression
     * @return string
     */
    public function getPattern()
    {
        $this->pattern = $this->pattern ?: $this->build();
        return $this->pattern;
    }

    /**
     * Transform expression to string
     * @return string
     */
    public function __toString()
    {
        return $this->getPattern();
    }
}
