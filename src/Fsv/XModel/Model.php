<?php
namespace Fsv\XModel;

use Fsv\XModel\Filter\FilterChain;
use Fsv\XModel\Mapping\Root;
use Fsv\XModel\Mapping\XPath;

/**
 * Class Model
 * @package Fsv\XModel
 */
class Model
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var Root
     */
    private $root;

    /**
     * @var XPath
     */
    private $xpath;

    /**
     * @var Property[]
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
     * @param Root $root
     * @return Model
     */
    public function setRoot(Root $root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * @return Root
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param Property $property
     * @return Model
     */
    public function addProperty(Property $property)
    {
        $this->properties[] = $property;

        return $this;
    }

    /**
     * @return Property[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param XPath $xpath
     * @return Property
     */
    public function setXPath(XPath $xpath)
    {
        $this->xpath = $xpath;

        return $this;
    }

    /**
     * @return XPath
     */
    public function getXPath()
    {
        return $this->xpath;
    }

    /**
     * @param FilterInterface $filter
     * @return Property
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
