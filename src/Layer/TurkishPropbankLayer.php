<?php

namespace olcaytaner\AnnotatedTree\Layer;

use olcaytaner\AnnotatedTree\Layer\SingleWordLayer;
use olcaytaner\Propbank\Argument;

class TurkishPropbankLayer extends SingleWordLayer
{
    private ?Argument $propbank = null;

    /**
     * Constructor for the Turkish propbank layer. Sets single semantic role information for multiple words in
     * the node.
     * @param string $layerValue Layer value for the propbank information. Consists of semantic role information
     *                   of multiple words.
     */
    public function __construct(string $layerValue){
        $this->setLayerValue($layerValue);
        $this->layerName = "propBank";
    }

    /**
     * Sets the layer value for Turkish propbank layer. Converts the string form to an Argument.
     * @param string $layerValue New value for Turkish propbank layer.
     */
    public function setLayerValue(string $layerValue): void{
        $this->layerValue = $layerValue;
        $this->propbank = new Argument($layerValue);
    }

    /**
     * Accessor for the propbank field.
     * @return Argument Propbank field.
     */
    public function getArgument(): Argument{
        return $this->propbank;
    }

    /**
     * Another accessor for the propbank field.
     * @return string String form of the propbank field.
     */
    public function getLayerValue(): string{
        return $this->propbank->getArgumentType() . "$" . $this->propbank->getId();
    }
}