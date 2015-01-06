<?php
namespace Fsv\XModel\Mapping;

use Fsv\XModel\AbstractAnnotation;
use InvalidArgumentException;

/**
 * Class Children
 * @package Fsv\XModel\Mapping
 *
 * @Annotation
 * @Target("PROPERTY")
 */
class Children extends AbstractAnnotation
{
    /**
     * @var string
     */
    public $className;

    /**
     * @var ModelMetadata
     */
    private $associatedModelMetadata;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        if (null === $this->className
            || !class_exists($this->className)
        ) {
            throw new InvalidArgumentException('"className" is required option');
        }
    }

    /**
     * @return string
     */
    public function getDefaultOptionName()
    {
        return 'className';
    }

    /**
     * @return ModelMetadata
     */
    public function getAssociatedModelMetadata()
    {
        if (null === $this->associatedModelMetadata) {
            $this->associatedModelMetadata = new ModelMetadata($this->className);
        }

        return $this->associatedModelMetadata;
    }
}
