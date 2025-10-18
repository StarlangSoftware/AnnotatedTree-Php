<?php

namespace olcaytaner\AnnotatedTree\Layer;

class TurkishSemanticLayer extends MultiWordLayer
{

    /**
     * Constructor for the Turkish semantic layer. Sets semantic information for each word in
     * the node.
     * @param string $layerValue Layer value for the Turkish semantic information. Consists of semantic (Turkish synset id)
     *                   information for every word.
     */
    public function __construct(string $layerValue)
    {
        $this->layerName = "semantics";
        $this->setLayerValue($layerValue);
    }

    /**
     * Sets the value for the Turkish semantic layer in a node. Value may consist of multiple sense information
     * separated via '$' character. Each sense value is a string representing the synset id of the sense.
     * @param string $layerValue New layer info
     */
    function setLayerValue(string $layerValue): void
    {
        $this->items = [];
        $this->layerValue = $layerValue;
        if ($layerValue != null){
            $splitMeanings = explode("$", $layerValue);
            foreach ($splitMeanings as $splitMeaning){
                $this->items[] = $splitMeaning;
            }
        }
    }
}