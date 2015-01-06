<?php
namespace Fsv\XModel;

use Fsv\XModel\Filter\FilterChain;
use Fsv\XModel\Mapping\Node;
use Fsv\XModel\Mapping\Children;
use Fsv\XModel\Mapping\XPath;
use Fsv\XModel\Transformer\TransformerChain;

/**
 * Class Property
 * @package Fsv\XModel
 */
class Property
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Node
     */
    private $node;

    /**
     * @var Children
     */
    private $children;

    /**
     * @var Model
     */
    private $model;

    /**
     * @var XPath
     */
    private $xpath;

    /**
     * @var TransformerChain
     */
    private $transformerChain;

    /**
     * @var FilterChain
     */
    private $filterChain;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->transformerChain = new TransformerChain();
        $this->filterChain = new FilterChain();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Node $node
     * @return Property
     */
    public function setNode(Node $node)
    {
        $this->node = $node;

        return $this;
    }

    /**
     * @param Children $children
     * @return Property
     */
    public function setChildren(Children $children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @return Children
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Model $model
     * @return Property
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
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
     * @param TransformerInterface $transformer
     * @return Property
     */
    public function addTranformer(TransformerInterface $transformer)
    {
        $this->transformerChain->add($transformer);

        return $this;
    }

    /**
     * @return transformerChain
     */
    public function getTransformerChain()
    {
        return $this->transformerChain;
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
