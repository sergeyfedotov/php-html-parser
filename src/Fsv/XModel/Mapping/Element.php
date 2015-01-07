<?php
namespace Fsv\XModel\Mapping;

use InvalidArgumentException;

/**
 * Class Element
 * @package Fsv\XModel\Mapping
 *
 * @Annotation
 * @Target("PROPERTY")
 */
class Element extends AbstractNode
{
    /**
     * @var string|null
     */
    public $className;

    /**
     * @var bool
     */
    public $hasMany = false;

    /**
     * @var ModelMetadata
     */
    private $associatedModelMetadata;

    /**
     * @return ModelMetadata
     */
    public function getAssociatedModelMetadata()
    {
        if (null === $this->className
            || !class_exists($this->className)
        ) {
            throw new InvalidArgumentException(sprintf('Class "%s" does not exists', $this->className));
        }

        if (null === $this->associatedModelMetadata) {
            $this->associatedModelMetadata = new ModelMetadata($this->className);
        }

        return $this->associatedModelMetadata;
    }
}
