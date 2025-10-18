<?php

namespace olcaytaner\AnnotatedTree\Processor\Condition;

use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\AnnotatedTree\ParseNodeDrawable;

class IsNumber extends IsLeafNode
{

    /**
     * Checks if the node is a leaf node and contains numerals as the data and its parent has the tag CD.
     * @param ParseNodeDrawable $parseNode Parse node to check.
     * @return bool True if the node is a leaf node and contains numerals as the data and its parent has the tag CD, false
     * otherwise.
     */
    public function satisfies(ParseNodeDrawable $parseNode): bool{
        if (parent::satisfies($parseNode)) {
            $data = $parseNode->getLayerData(ViewLayerType::ENGLISH_WORD);
            $parentData = $parseNode->getParent()->getData()->getName();
            return $parentData === 'CD' && is_numeric($data);
        }
        return false;
    }
}