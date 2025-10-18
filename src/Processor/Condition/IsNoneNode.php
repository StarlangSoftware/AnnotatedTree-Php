<?php

namespace olcaytaner\AnnotatedTree\Processor\Condition;

use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\AnnotatedTree\ParseNodeDrawable;

class IsNoneNode extends IsLeafNode
{
    private ViewLayerType $secondLanguage;

    public function __construct(ViewLayerType $secondLanguage){
        $this->secondLanguage = $secondLanguage;
    }

    /**
     * Checks if the data of the parse node is '*NONE*'.
     * @param ParseNodeDrawable $parseNode Parse node to check.
     * @return bool True if the data of the parse node is '*NONE*', false otherwise.
     */
    public function satisfies(ParseNodeDrawable $parseNode): bool
    {
        if (parent::satisfies($parseNode)){
            $data = $parseNode->getLayerData($this->secondLanguage);
            return $data != null && $data == "*NONE*";
        }
        return false;
    }
}