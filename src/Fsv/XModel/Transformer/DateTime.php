<?php
namespace Fsv\XModel\Transformer;

use Fsv\XModel\TransformerInterface;

/**
 * Class DateTime
 * @package Fsv\XModel\Transformer
 *
 * @Annotation
 * @Target("PROPERTY")
 */
class DateTime implements TransformerInterface
{
    /**
     * @param mixed $value
     * @return \DateTime
     */
    public function transform($value)
    {
        return new \DateTime($value);
    }
}
