<?php
namespace Fsv\XModel;

/**
 * Class AbstractAnnotation
 * @package Fsv\XModel
 */
abstract class AbstractAnnotation
{
    /**
     * @var mixed
     */
    public $value;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        foreach (get_object_vars($this) as $name) {
            if (isset($options[$name])) {
                $this->{$name} = $options[$name];
            }
        }

        if (null !== ($defaultOptionName = $this->getDefaultOptionName())
            && isset($options['value'])
        ) {
            $this->{$defaultOptionName} = $options['value'];
        }
    }

    /**
     * @return string|null
     */
    public function getDefaultOptionName()
    {
        return null;
    }
}
