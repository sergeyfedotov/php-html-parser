<?php
namespace Fsv\XModel\Mapping;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Children
{
    /**
     * @var string
     */
    private $className;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->className = isset($attributes['value']) ? $attributes['value'] : null;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }
}
