<?php

namespace olcaytaner\AnnotatedTree\Processor\Condition;

use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\AnnotatedTree\ParseNodeDrawable;
use olcaytaner\AnnotatedTree\Processor\Condition\IsLeafNode;

class IsTransferable extends IsLeafNode
{
    private ViewLayerType $secondLanguage;

    public function __construct(ViewLayerType $secondLanguage){
        $this->secondLanguage = $secondLanguage;
    }

    /**
     * Checks if the node is a leaf node and is not a None or Null node.
     * @param ParseNodeDrawable $parseNode Parse node to check.
     * @return bool True if the node is a leaf node and is not a None or Null node, false otherwise.
     */
    public function satisfies(ParseNodeDrawable $parseNode): bool{
        if (parent::satisfies($parseNode)) {
            if (new IsNoneNode($this->secondLanguage)->satisfies($parseNode)) {
                return true;
            }
            return !new IsNullElement()->satisfies($parseNode);
        }
        return false;
    }
}