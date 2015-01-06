<?php
namespace Fsv\XModel\Mapping;

/**
 * Class AbstractMetadata
 * @package Fsv\XModel\Mapping
 */
abstract class AbstractMetadata
{
    /**
     * @var AbstractNode
     */
    protected $node;

    /**
     * @param AbstractNode $node
     * @return AbstractMetadata
     */
    public function setNode(AbstractNode $node)
    {
        $this->node = $node;

        return $this;
    }

    /**
     * @return AbstractNode
     */
    public function getNode()
    {
        return $this->node;
    }
}
