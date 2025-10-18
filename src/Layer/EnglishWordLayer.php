<?php

namespace olcaytaner\AnnotatedTree\Layer;

class EnglishWordLayer extends SourceLanguageWordLayer
{
    /**
     * Constructor for the word layer for English language. Sets the surface form.
     * @param string $layerValue Value for the word layer.
     */
    public function __construct(string $layerValue){
        parent::__construct($layerValue);
        $this->layerName = "english";
    }
}