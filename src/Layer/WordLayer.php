<?php

namespace olcaytaner\AnnotatedTree\Layer;

abstract class WordLayer
{
    protected string $layerValue;
    protected string $layerName;

    /**
     * Accessor for the layerValue attribute.
     * @return string LayerValue attribute.
     */
    public function getLayerValue(): string{
        return $this->layerValue;
    }

    /**
     * Accessor for the layerName attribute.
     * @return string LayerName attribute.
     */
    public function getLayerName(): string{
        return $this->layerName;
    }

    /**
     * Returns string form of the word layer.
     * @return string String form of the word layer.
     */
    public function getLayerDescription(): string{
        return "{" . $this->layerName . "=" . $this->layerValue . "}";
    }
}