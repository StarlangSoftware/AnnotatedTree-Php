<?php

namespace olcaytaner\AnnotatedTree\Processor\LeafConverter;

use olcaytaner\AnnotatedSentence\ViewLayerType;

class LeafToPersian extends LeafToLanguageConverter
{
    /**
     * Constructor for LeafToPersian. Sets viewLayerType to PERSIAN.
     */
    public function __construct(){
        $this->viewLayerType = ViewLayerType::PERSIAN_WORD;
    }
}