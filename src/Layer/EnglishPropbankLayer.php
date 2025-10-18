<?php

namespace olcaytaner\AnnotatedTree\Layer;

use olcaytaner\Propbank\Argument;

class EnglishPropbankLayer extends SingleWordMultiItemLayer
{
    /**
     * Constructor for the propbank layer for English language.
     * @param string $layerValue Value for the English propbank layer.
     */
    public function __construct(string $layerValue){
        $this->layerName = "englishPropbank";
        $this->setLayerValue($layerValue);
    }

    /**
     * Sets the value for the propbank layer in a node. Value may consist of multiple propbank information separated via
     * '#' character. Each propbank value consists of argumentType and id info separated via '$' character.
     * @param string $layerValue New layer info
     */
    public function setLayerValue(string $layerValue): void
    {
        $this->items = [];
        $this->layerName = $layerValue;
        if ($layerValue != null){
            $splitWords = explode("#", $layerValue);
            foreach ($splitWords as $splitWord){
                $this->items[] = new Argument($splitWord);
            }
        }
    }
}