<?php

namespace olcaytaner\AnnotatedTree\Layer;

use olcaytaner\NamedEntityRecognition\NamedEntityTypeStatic;

class PersianWordLayer extends TargetLanguageWordLayer
{
    /**
     * Constructor for the word layer for Persian language. Sets the surface form.
     * @param string $layerValue Value for the word layer.
     */
    public function __construct(string $layerValue)
    {
        parent::__construct($layerValue);
        $this->layerName = "persian";
    }
}