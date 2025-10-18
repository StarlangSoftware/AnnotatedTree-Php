<?php

namespace olcaytaner\AnnotatedTree\Layer;

class ShallowParseLayer extends MultiWordLayer
{

    /**
     * Constructor for the shallow parse layer. Sets shallow parse information for each word in
     * the node.
     * @param string $layerValue Layer value for the shallow parse information. Consists of shallow parse information
     *                   for every word.
     */
    public function __construct(string $layerValue){
        $this->layerName = "shallowParse";
        $this->setLayerValue($layerValue);
    }

    /**
     * Sets the value for the shallow parse layer in a node. Value may consist of multiple shallow parse information
     * separated via space character. Each shallow parse value is a string.
     * @param string $layerValue New layer info
     */
    function setLayerValue(string $layerValue): void
    {
        $this->items = [];
        $this->layerValue = $layerValue;
        if ($layerValue != null){
            $splitParse = explode(" ", $layerValue);
            foreach ($splitParse as $item){
                $this->items[] = $item;
            }
        }
    }
}