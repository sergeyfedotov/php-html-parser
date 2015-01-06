<?php
namespace Fsv\XModel\Transformer;

use Fsv\XModel\TransformerInterface;

/**
 * Class Trim
 * @package Fsv\XModel\Transformer
 *
 * @Annotation
 * @Target("PROPERTY")
 */
class Trim implements TransformerInterface
{
    /**
     * @param mixed $value
     * @return string
     */
    public function transform($value)
    {
        return trim($value);
    }
}
