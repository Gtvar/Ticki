<?php

namespace Ticki\Core\Strategy;

use Ticki\Core\Exception\ExceptionFactory;

/**
 * Class StrategyManager
 */
class StrategyManager
{
    /**
     * @var StrategyInterface[]
     */
    protected $strategies = array();

    /**
     * Construct
     *
     * Simple Container
     */
    public function __construct()
    {
        $strategy = new SimpleStrategy();
        $this->strategies[$strategy->name()] = $strategy;

	    $strategy = new IntelligentStrategy();
        $this->strategies[$strategy->name()] = $strategy;
    }

    /**
     * Get Strategies
     *
     * @return StrategyInterface[]
     */
    public function getStrategies()
    {
        return $this->strategies;
    }

    /**
     * Get array of names
     *
     * @return array
     */
    public function getStrategiesNames()
    {
        return array_keys($this->strategies);
    }

    /**
     * Get game strategy by name
     *
     * @param $name
     *
     * @return mixed
     * @throws \Ticki\Core\Exception\UnknownStrategyException
     */
    public function getByName($name)
    {
        if (!isset($this->strategies[$name])) {
            throw ExceptionFactory::undefinedStrategyException($name);
        }

        return $this->strategies[$name];
    }
}
