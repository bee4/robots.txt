<?php

namespace Bee4\RobotsTxt\Exception;

use LogicException;
use Bee4\RobotsTxt\Rule;

/**
 * Class DuplicateRuleException
 * Error thrown when the parser try to add 2 rules for the same UA
 *
 * @copyright Bee4 2015
 * @author    Stephane HULARD <s.hulard@chstudio.fr>
 */
class DuplicateRuleException extends LogicException
{
    /**
     * @var Rule
     */
    protected $rule;

    /**
     * Rule setter
     * @param Rule $rule
     * @return DuplicateRuleException
     */
    public function setRule(Rule $rule)
    {
        $this->rule = $rule;
        return $this;
    }

    /**
     * @return Rule
     */
    public function getRule()
    {
        return $this->rule;
    }
}
