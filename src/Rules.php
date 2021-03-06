<?php

namespace Bee4\RobotsTxt;

use Bee4\RobotsTxt\Exception\DuplicateRuleException;
use Bee4\RobotsTxt\Exception\InvalidUrlException;

/**
 * Class Rules
 * Represent a collection of Rules
 *
 * @copyright Bee4 2015
 * @author    Stephane HULARD <s.hulard@chstudio.fr>
 */
class Rules implements \Countable, \IteratorAggregate
{
    const DEFAULT_UA = '*';

    /**
     * The collection of rules
     * @var array
     */
    protected $collection = [];

    /**
     * Sitemap collection
     * @var array
     */
    protected $sitemaps = [];

    /**
     * Default rule used if robots.txt is empty
     * @var Rule
     */
    private $defaultRule;

    public function __construct()
    {
        $this->defaultRule = new Rule(self::DEFAULT_UA);
        $this->add($this->defaultRule);
    }

    /**
     * Add a sitemap in the current rule set
     * @param string $sitemap
     * @return Rules
     */
    public function addSitemap($sitemap)
    {
        if (!filter_var($sitemap, FILTER_VALIDATE_URL)) {
            throw (new InvalidUrlException(sprintf('Invalid sitemap URL given: %s', $sitemap)))
                ->setUrl($sitemap);
        }
        $this->sitemaps[] = $sitemap;
        return $this;
    }

    /**
     * Retrieve the sitemap collection
     * @return array
     */
    public function getSitemaps()
    {
        return $this->sitemaps;
    }

    /**
     * Add a new rule to the collection
     * @param Rule $rule
     * @return Rules
     */
    public function add(Rule $rule)
    {
        $userAgent = $this->handleUa($rule->getUserAgent());
        if (isset($this->collection[$userAgent]) &&
                $this->collection[$userAgent] !== $this->defaultRule ) {
            throw (new DuplicateRuleException(
                'You can\'t add 2 rules for the same UserAgent'
            ))
                ->setRule($rule);
        }
        $this->collection[$userAgent] = $rule;

        return $this;
    }

    /**
     * Check if the URL match for the given UA or not
     * @param string $userAgent
     * @param string $url
     * @return boolean
     */
    public function match($userAgent, $url)
    {
        return $this->get($userAgent)->match($url);
    }

    /**
     * Retrieve rules for a given UA
     * @param string $userAgent
     * @return null|Rule
     */
    public function get($userAgent)
    {
        $item = null;
        $iterator = new \ArrayIterator($this->collection);
        iterator_apply(
            $iterator,
            function (\ArrayIterator $iterator, $userAgent) use (&$item) {
                if ($iterator->key() != Rules::DEFAULT_UA &&
                        preg_match($iterator->key(), $userAgent) === 1 ) {
                    $item = $iterator->current();
                    return false;
                }
                return true;
            },
            [$iterator, $userAgent]
        );

        return $item!==null?
            $item:
            $this->collection[self::DEFAULT_UA];
    }

    /**
     * Update the UA to make a valid regexp
     * @param string $userAgent
     * @return string
     */
    private function handleUa($userAgent)
    {
        if ($userAgent == self::DEFAULT_UA) {
            return $userAgent;
        }
        return '/^'.preg_quote($userAgent).'.*/i';
    }

    /**
     * Return the number of rules
     * @return integer
     */
    public function count()
    {
        return count($this->collection);
    }

    /**
     * IteratorAggregate implementation
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->collection);
    }
}
