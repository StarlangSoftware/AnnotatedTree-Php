<?php

namespace olcaytaner\AnnotatedTree\Layer;

use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\MorphologicalAnalysis\MorphologicalAnalysis\MetamorphicParse;

class MetaMorphemesMovedLayer extends MultiWordMultiItemLayer
{

    /**
     * Constructor for the metaMorphemesMoved layer. Sets the metamorpheme information for multiple words in the node.
     * @param string $layerValue Layer value for the metaMorphemesMoved information. Consists of metamorpheme information of
     *                   multiple words separated via space character.
     */
    public function __construct(string $layerValue)
    {
        $this->layerName = "metaMorphemesMoved";
        $this->setLayerValue($layerValue);
    }

    /**
     * Sets the layer value to the string form of the given parse.
     * @param string $layerValue New metamorphic parse.
     */
    function setLayerValue(string $layerValue): void
    {
        $this->items = [];
        $this->layerValue = $layerValue;
        if ($layerValue != null){
            $splitWords = explode(" ", $layerValue);
            foreach ($splitWords as $word){
                $this->items[] = new MetamorphicParse($word);
            }
        }
    }

    /**
     * Returns the total number of metamorphemes in the words in the node.
     * @param ViewLayerType $viewLayer Not used.
     * @return int Total number of metamorphemes in the words in the node.
     */
    function getLayerSize(ViewLayerType $viewLayer): int
    {
        $size = 0;
        /** @var MetamorphicParse $parse */
        foreach ($this->items as $parse) {
            $size += $parse->size();
        }
        return $size;
    }

    /**
     * Returns the metamorpheme at position index in the metamorpheme list.
     * @param ViewLayerType $viewLayer Not used.
     * @param int $index Position in the metamorpheme list.
     * @return string|null The metamorpheme at position index in the metamorpheme list.
     */
    function getLayerInfoAt(ViewLayerType $viewLayer, int $index): ?string
    {
        $size = 0;
        /** @var MetamorphicParse $parse */
        foreach ($this->items as $parse) {
            if ($index < $size + $parse->size()) {
                return $parse->getMetaMorpheme($index - $size);
            }
            $size += $parse->size();
        }
        return null;
    }
}