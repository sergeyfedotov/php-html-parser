<?php
namespace Fsv\XModel\Mapping;

use Fsv\XModel\Filter\FilterChain;
use Fsv\XModel\FilterInterface;
use Fsv\XModel\Transformer\TransformerChain;
use Fsv\XModel\TransformerInterface;

/**
 * Class PropertyMetadata
 * @package Fsv\XModel\Mapping
 */
class PropertyMetadata extends AbstractMetadata
{
    /**
     * @var string
     */
    private $name;

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
     * @param TransformerInterface $transformer
     * @return PropertyMetadata
     */
    public function addTransformer(TransformerInterface $transformer)
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
     * @return PropertyMetadata
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
