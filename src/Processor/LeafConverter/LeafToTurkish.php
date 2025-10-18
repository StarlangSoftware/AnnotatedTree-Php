<?php

namespace olcaytaner\AnnotatedTree\Processor\LeafConverter;

use olcaytaner\AnnotatedSentence\ViewLayerType;

class LeafToTurkish extends LeafToLanguageConverter
{
    /**
     * Constructor for LeafToPersian. Sets viewLayerType to TURKISH.
     */
    public function __construct(){
        $this->viewLayerType = ViewLayerType::TURKISH_WORD;
    }

}