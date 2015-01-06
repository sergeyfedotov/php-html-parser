<?php
namespace Fsv\XModel;

/**
 * Interface TransformerInterface
 * @package Fsv\XModel
 */
interface TransformerInterface
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function transform($value);
}
