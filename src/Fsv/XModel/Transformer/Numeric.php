<?php
namespace Fsv\XModel\Transformer;

use Fsv\XModel\TransformerInterface;

/**
 * Class Numeric
 * @package Fsv\XModel\Transformer
 *
 * @Annotation
 * @Target("PROPERTY")
 */
class Numeric implements TransformerInterface
{
    /**
     * @param mixed $value
     * @return string
     */
    public function transform($value)
    {
        return (float)preg_replace('/[^.\d]/', '', $value);
    }
}
