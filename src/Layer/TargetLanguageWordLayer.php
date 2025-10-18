<?php

namespace olcaytaner\AnnotatedTree\Layer;

use olcaytaner\AnnotatedSentence\ViewLayerType;

class TargetLanguageWordLayer extends MultiWordLayer
{

    /**
     * Sets the surface form(s) of the word(s) possibly separated with space.
     * @param string $layerValue Surface form(s) of the word(s) possibly separated with space.
     */
    public function __construct(string $layerValue){
        $this->setLayerValue($layerValue);
    }

    /**
     * Sets the surface form(s) of the word(s). Value may consist of multiple surface form(s)
     * separated via space character.
     * @param string $layerValue New layer info
     */
    function setLayerValue(string $layerValue): void
    {
        $this->items = [];
        $this->layerValue = $layerValue;
        if ($layerValue != null) {
            $splitWords = explode(' ', $layerValue);
            foreach ($splitWords as $splitWord) {
                $this->items[] = $splitWord;
            }
        }
    }

    public function getLayerSize(ViewLayerType $viewLayerType): int{
        return 0;
    }

    public function getLayerInfoAt(ViewLayerType $viewLayer, int $index): ?string{
        return null;
    }
}