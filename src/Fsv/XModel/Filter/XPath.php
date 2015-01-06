<?php
namespace Fsv\XModel\Filter;

use DOMNode;
use DOMNodeList;
use DOMXPath;

/**
 * Class XPath
 * @package Fsv\XModel\Filter
 *
 * @Annotation
 * @Target({"CLASS", "PROPERTY"})
 */
class XPath extends AbstractFilter
{
    /**
     * @var string
     */
    public $query;

    /**
     * @return string
     */
    public function getDefaultOptionName()
    {
        return 'query';
    }

    /**
     * @param DOMXPath $xpath
     * @param array|DOMNodeList $nodes
     * @return DOMNode[]
     */
    public function apply(DOMXPath $xpath, $nodes)
    {
        $reduced = [];

        foreach ($nodes as $contextNode) {
            foreach ($xpath->query($this->query, $contextNode) as $childNode) {
                $reduced[] = $childNode;
            }
        }

        return $reduced;
    }
}
