<?php
namespace Fsv\XModel\Transformer;

use Fsv\XModel\TransformerInterface;

/**
 * Class TransformerChain
 * @package Fsv\XModel\Transformer
 *
 * @Annotation
 * @Target("PROPERTY")
 */
class TransformerChain implements TransformerInterface
{
    /**
     * @var TransformerInterface[]
     */
    private $children = [];

    /**
     * @param TransformerInterface $filter
     * @return TransformerChain
     */
    public function add(TransformerInterface $filter)
    {
        $this->children[] = $filter;

        return $this;
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function transform($value)
    {
        foreach ($this->children as $filter) {
            $value = $filter->transform($value);
        }

        return $value;
    }
}
