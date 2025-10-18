<?php

namespace olcaytaner\AnnotatedTree\Processor\Condition;

use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\AnnotatedTree\ParseNodeDrawable;
use olcaytaner\AnnotatedTree\Processor\Condition\IsLeafNode;

class IsTurkishLeafNode extends IsLeafNode
{
    /**
     * Checks if the parse node is a leaf node and contains a valid Turkish word in its data.
     * @param ParseNodeDrawable $parseNode Parse node to check.
     * @return bool True if the parse node is a leaf node and contains a valid Turkish word in its data; false otherwise.
     */
    public function satisfies(ParseNodeDrawable $parseNode): bool{
        if (parent::satisfies($parseNode)) {
            $data = $parseNode->getLayerInfo()->getLayerData(ViewLayerType::TURKISH_WORD);
            $parentData = $parseNode->getParent()->getData()->getName();
            return $data != null && !str_contains($data, "*") && !($data == "0" && $parentData == "-NONE-");
        }
        return false;
    }
}