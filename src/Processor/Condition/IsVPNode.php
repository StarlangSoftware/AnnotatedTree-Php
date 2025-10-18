<?php

namespace olcaytaner\AnnotatedTree\Processor\Condition;

use olcaytaner\AnnotatedTree\ParseNodeDrawable;
use olcaytaner\AnnotatedTree\Processor\Condition\NodeDrawableCondition;

class IsVPNode implements NodeDrawableCondition
{

    /**
     * Checks if the node is not a leaf node and its tag is VP.
     * @param ParseNodeDrawable $parseNode Parse node to check.
     * @return bool True if the node is not a leaf node and its tag is VP, false otherwise.
     */
    public function satisfies(ParseNodeDrawable $parseNode): bool
    {
        return $parseNode->numberOfChildren() > 0 && $parseNode->getData()->isVP();
    }
}