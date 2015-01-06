<?php
namespace Fsv\XModel\Mapping;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Node
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->name = isset($attributes['value']) ? $attributes['value'] : null;
    }

    /**
     * @param string $name
     * @return Node
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
