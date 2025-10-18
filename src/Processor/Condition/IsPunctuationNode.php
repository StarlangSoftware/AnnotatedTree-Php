<?php

namespace olcaytaner\AnnotatedTree\Processor\Condition;

use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\AnnotatedTree\ParseNodeDrawable;
use olcaytaner\Dictionary\Dictionary\Word;

class IsPunctuationNode extends IsLeafNode
{
    /**
     * Checks if the node is a leaf node and contains punctuation as the data.
     * @param ParseNodeDrawable $parseNode Parse node to check.
     * @return bool True if the node is a leaf node and contains punctuation as the data, false otherwise.
     */
    public function satisfies(ParseNodeDrawable $parseNode): bool{
        if (parent::satisfies($parseNode)) {
            $data = $parseNode->getLayerData(ViewLayerType::ENGLISH_WORD);
            return Word::isPunctuationSymbol($data) && $data != "$";
        }
        return false;
    }
}