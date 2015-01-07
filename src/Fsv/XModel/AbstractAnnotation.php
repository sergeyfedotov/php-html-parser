<?php
namespace Fsv\XModel;

use InvalidArgumentException;

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
        if (null !== ($defaultOptionName = $this->getDefaultOptionName())
            && isset($options['value'])
        ) {
            $options[$defaultOptionName] = $options['value'];
        }

        foreach ($this->getRequiredOptionNames() as $name) {
            if (!isset($options[$name])) {
                throw new InvalidArgumentException(sprintf('Option "%s" is required', $name));
            }
        }

        foreach (get_object_vars($this) as $name => $value) {
            if (isset($options[$name])) {
                $this->{$name} = $options[$name];
            }
        }
    }

    /**
     * @return string|null
     */
    public function getDefaultOptionName()
    {
        return null;
    }

    /**
     * @return array
     */
    public function getRequiredOptionNames()
    {
        return [];
    }
}
