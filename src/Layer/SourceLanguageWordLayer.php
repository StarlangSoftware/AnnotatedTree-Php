<?php

namespace olcaytaner\AnnotatedTree\Layer;

class SourceLanguageWordLayer extends SingleWordLayer
{
    /**
     * Sets the name of the word
     * @param string $layerValue Name of the word
     */
    public function __construct(string $layerValue){
        $this->setLayerValue($layerValue);
    }
}