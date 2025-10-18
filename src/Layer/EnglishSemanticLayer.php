<?php

namespace olcaytaner\AnnotatedTree\Layer;

class EnglishSemanticLayer extends SingleWordLayer
{
    /**
     * Constructor for the semantic layer for English language. Sets the layer value to the synset id defined in English
     * WordNet.
     * @param string $layerValue Value for the English semantic layer.
     */
    public function __construct(string $layerValue){
        $this->layerName = "englishSemantics";
        $this->setLayerValue($layerValue);
    }
}