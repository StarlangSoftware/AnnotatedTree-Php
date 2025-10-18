<?php

namespace olcaytaner\AnnotatedTree\Layer;

abstract class SingleWordLayer extends WordLayer
{

    /**
     * Sets the property of the word
     * @param string $layerValue Layer info
     */
    public function setLayerValue(string $layerValue): void{
        $this->layerValue = $layerValue;
    }
}