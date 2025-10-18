<?php

namespace olcaytaner\AnnotatedTree\Layer;


class TurkishWordLayer extends TargetLanguageWordLayer
{
    /**
     * Constructor for the word layer for Turkish language. Sets the surface form.
     * @param string $layerValue Value for the word layer.
     */
    public function __construct(string $layerValue)
    {
        parent::__construct($layerValue);
        $this->layerName = "turkish";
    }
}