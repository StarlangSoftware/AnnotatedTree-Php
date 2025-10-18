<?php

namespace olcaytaner\AnnotatedTree\Processor\LeafConverter;

use olcaytaner\AnnotatedSentence\ViewLayerType;

class LeafToEnglish extends LeafToLanguageConverter
{
    /**
     * Constructor for LeafToEnglish. Sets viewLayerType to ENGLISH.
     */
    public function __construct(){
        $this->viewLayerType = ViewLayerType::ENGLISH_WORD;
    }
}