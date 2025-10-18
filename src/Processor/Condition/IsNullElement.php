<?php

namespace olcaytaner\AnnotatedTree\Processor\Condition;

use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\AnnotatedTree\ParseNodeDrawable;

class IsNullElement extends IsLeafNode
{
    /**
     * Checks if the parse node is a leaf node and its data is '*' and its parent's data is '-NONE-'.
     * @param ParseNodeDrawable $parseNode Parse node to check.
     * @return bool True if the parse node is a leaf node and its data is '*' and its parent's data is '-NONE-', false
     * otherwise.
     */
    public function satisfies(ParseNodeDrawable $parseNode): bool
    {
        if (parent::satisfies($parseNode)){
            $data = $parseNode->getLayerData(ViewLayerType::ENGLISH_WORD);
            $parentData = $parseNode->getParent()->getData()->getName();
            return str_contains($data, "*") || ($data == "0" && $parentData == "-NONE-");
        }
        return false;
    }
}