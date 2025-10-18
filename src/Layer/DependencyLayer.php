<?php

namespace olcaytaner\AnnotatedTree\Layer;

class DependencyLayer extends SingleWordLayer
{
    /**
     * Constructor for the dependency layer. Dependency layer stores the dependency information of a node.
     * @param string $layerValue Value of the dependency layer.
     */
    public function __construct(string $layerValue){
        $this->layerName = "dependency";
        $this->setLayerValue($layerValue);
    }
}