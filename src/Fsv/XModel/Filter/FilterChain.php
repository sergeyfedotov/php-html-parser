<?php
namespace Fsv\XModel\Filter;

use DOMNode;
use DOMNodeList;
use DOMXPath;
use Fsv\XModel\FilterInterface;

/**
 * Class XPath
 * @package Fsv\XModel\Filter
 *
 * @Annotation
 * @Target({"CLASS", "PROPERTY"})
 */
class FilterChain extends AbstractFilter
{
    /**
     * @var FilterInterface[]
     */
    private $children = [];

    /**
     * @param FilterInterface $filter
     * @return FilterInterface
     */
    public function add(FilterInterface $filter)
    {
        $this->children[] = $filter;

        return $this;
    }

    /**
     * @param DOMXPath $xpath
     * @param array|DOMNodeList $nodes
     * @return DOMNode[]
     */
    public function apply(DOMXPath $xpath, $nodes)
    {
        $reduced = [];

        foreach ($this->children as $i => $filter) {
            if ($i > 0) {
                $nodes = $reduced;
                $reduced = [];
            }

            foreach ($filter->apply($xpath, $nodes) as $node) {
                $reduced[] = $node;
            }
        }

        return $reduced;
    }
}
