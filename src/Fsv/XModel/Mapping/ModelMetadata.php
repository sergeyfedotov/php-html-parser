<?php
namespace Fsv\XModel\Mapping;

use Fsv\XModel\Filter\FilterChain;
use Fsv\XModel\FilterInterface;

/**
 * Class ModelMetadata
 * @package Fsv\XModel\Mapping
 */
class ModelMetadata extends AbstractMetadata
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var PropertyMetadata[]
     */
    private $properties = [];

    /**
     * @var FilterChain
     */
    private $filterChain;

    /**
     * @param string $className
     */
    public function __construct($className)
    {
        $this->className = $className;
        $this->filterChain = new FilterChain();
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param PropertyMetadata $property
     * @return ModelMetadata
     */
    public function addProperty(PropertyMetadata $property)
    {
        $this->properties[] = $property;

        return $this;
    }

    /**
     * @return PropertyMetadata[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param FilterInterface $filter
     * @return ModelMetadata
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filterChain->add($filter);

        return $this;
    }

    /**
     * @return FilterChain
     */
    public function getFilterChain()
    {
        return $this->filterChain;
    }
}
