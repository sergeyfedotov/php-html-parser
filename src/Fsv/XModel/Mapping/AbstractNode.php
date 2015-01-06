<?php
namespace Fsv\XModel\Mapping;

use Fsv\XModel\AbstractAnnotation;

/**
 * Class AbstractNode
 * @package Fsv\XModel\Mapping
 */
abstract class AbstractNode extends AbstractAnnotation
{
    /**
     * @var string
     */
    public $name;

    /**
     * @return string
     */
    public function getDefaultPropertyName()
    {
        return 'name';
    }
}
