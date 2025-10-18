<?php

namespace olcaytaner\AnnotatedTree\Processor\Condition;

use olcaytaner\AnnotatedTree\ParseNodeDrawable;

class IsLeafNode implements NodeDrawableCondition
{

    /**
     * Checks if the parse node is a leaf node, i.e., it has no child.
     * @param ParseNodeDrawable $parseNode Parse node to check.
     * @return bool True if the parse node is a leaf node, false otherwise.
     */
    public function satisfies(ParseNodeDrawable $parseNode): bool
    {
        return $parseNode->numberOfChildren() == 0;
    }
}