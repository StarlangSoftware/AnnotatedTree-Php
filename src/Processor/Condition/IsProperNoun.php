<?php

namespace olcaytaner\AnnotatedTree\Processor\Condition;

use olcaytaner\AnnotatedTree\ParseNodeDrawable;
use olcaytaner\AnnotatedTree\Processor\Condition\IsLeafNode;

class IsProperNoun extends IsLeafNode
{
    /**
     * Checks if the node is a leaf node and its parent has the tag NNP or NNPS.
     * @param ParseNodeDrawable $parseNode Parse node to check.
     * @return bool True if the node is a leaf node and its parent has the tag NNP or NNPS, false otherwise.
     */
    public function satisfies(ParseNodeDrawable $parseNode): bool{
        if (parent::satisfies($parseNode)) {
            $parentData = $parseNode->getParent()->getData()->getName();
            return $parentData == "NNP" || $parentData == "NNPS";
        }
        return false;
    }
}