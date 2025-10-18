<?php

namespace olcaytaner\AnnotatedTree\Processor\Condition;

use olcaytaner\AnnotatedTree\ParseNodeDrawable;

class IsEnglishLeafNode extends IsLeafNode
{
    /**
     * Checks if the parse node is a leaf node and contains a valid English word in its data.
     * @param ParseNodeDrawable $parseNode Parse node to check.
     * @return bool True if the parse node is a leaf node and contains a valid English word in its data; false otherwise.
     */
    public function satisfies(ParseNodeDrawable $parseNode): bool{
        if (parent::satisfies($parseNode)) {
            return !new IsNullElement()->satisfies($parseNode);
        }
        return false;
    }
}