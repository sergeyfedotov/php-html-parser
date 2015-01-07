<?php
namespace Fsv\XModel\Filter;

use DOMNode;
use DOMNodeList;
use DOMXPath;
use Symfony\Component\CssSelector\CssSelector as Selector;

/**
 * Class CssSelector
 * @package Fsv\XModel\Filter
 *
 * @Annotation
 * @Target({"CLASS", "PROPERTY"})
 */
class CssSelector extends XPath
{
    /**
     * @var string
     */
    public $selector;

    /**
     * @return string
     */
    public function getDefaultOptionName()
    {
        return 'selector';
    }

    /**
     * @return array
     */
    public function getRequiredOptionNames()
    {
        return ['selector'];
    }

    /**
     * @param DOMXPath $xpath
     * @param array|DOMNodeList $nodes
     * @return DOMNode[]|void
     */
    public function apply(DOMXPath $xpath, $nodes)
    {
        $this->query = Selector::toXPath($this->selector);

        return parent::apply($xpath, $nodes);
    }
}
