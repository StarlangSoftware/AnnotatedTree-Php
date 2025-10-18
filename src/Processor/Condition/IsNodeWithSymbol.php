<?php

namespace olcaytaner\AnnotatedTree\Processor\Condition;

use olcaytaner\AnnotatedTree\ParseNodeDrawable;

class IsNodeWithSymbol implements NodeDrawableCondition
{
    private string $symbol;

    /**
     * Stores the symbol to check.
     * @param string $symbol Symbol to check
     */
    public function __construct(string $symbol){
        $this->symbol = $symbol;
    }

    /**
     * Checks if the tag of the parse node is equal to the given symbol.
     * @param ParseNodeDrawable $parseNode Parse node to check.
     * @return bool True if the tag of the parse node is equal to the given symbol, false otherwise.
     */
    public function satisfies(ParseNodeDrawable $parseNode): bool
    {
        if ($parseNode->numberOfChildren() > 0){
            return $parseNode->getData()->getName() === $this->symbol;
        } else {
            return false;
        }
    }
}