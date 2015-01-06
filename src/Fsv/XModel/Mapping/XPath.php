<?php
namespace Fsv\XModel\Mapping;

/**
 * @Annotation
 * @Target({"CLASS", "PROPERTY"})
 */
class XPath
{
    /**
     * @var string
     */
    private $query;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->query = isset($attributes['value']) ? $attributes['value'] : null;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }
}
