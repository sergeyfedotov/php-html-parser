<?php
namespace Fsv\XModel;

use DOMNode;
use DOMNodeList;
use DOMXPath;

/**
 * Interface FilterInterface
 * @package Fsv\XModel
 */
interface FilterInterface
{
    /**
     * @param DOMXPath $xpath
     * @param array|DOMNodeList $nodes
     * @return DOMNode[]
     */
    public function apply(DOMXPath $xpath, $nodes);
}
