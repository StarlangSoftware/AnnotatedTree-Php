<?php

namespace olcaytaner\AnnotatedTree\Layer;

use olcaytaner\NamedEntityRecognition\NamedEntityType;
use olcaytaner\NamedEntityRecognition\NamedEntityTypeStatic;

class NERLayer extends SingleWordLayer
{
    private ?NamedEntityType $namedEntity = null;

    /**
     * Constructor for the named entity layer. Sets single named entity information for multiple words in
     * the node.
     * @param string $layerValue Layer value for the named entity information. Consists of single named entity information
     *                   of multiple words.
     */
    public function __construct(string $layerValue){
        $this->layerName = "namedEntity";
        $this->setLayerValue($layerValue);
    }

    /**
     * Sets the layer value for Named Entity layer. Converts the string form to a named entity.
     * @param string $layerValue New value for Named Entity layer.
     */
    public function setLayerValue(string $layerValue): void
    {
        $this->layerValue = $layerValue;
        $this->namedEntity = NamedEntityTypeStatic::getNamedEntityType($layerValue);
    }

    /**
     * Get the string form of the named entity value. Converts named entity type to string form.
     * @return string String form of the named entity value.
     */
    public function getLayerValue(): string
    {
        return NamedEntityTypeStatic::getNamedEntity($this->namedEntity);
    }
}