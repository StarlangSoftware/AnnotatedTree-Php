<?php

namespace olcaytaner\AnnotatedTree\Layer;

use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\AnnotatedTree\Layer\MultiWordMultiItemLayer;
use olcaytaner\MorphologicalAnalysis\MorphologicalAnalysis\MorphologicalParse;

class MorphologicalAnalysisLayer extends MultiWordMultiItemLayer
{

    /**
     * Constructor for the morphological analysis layer. Sets the morphological parse information for multiple words in
     * the node.
     * @param string $layerValue Layer value for the morphological parse information. Consists of morphological parse information
     *                   of multiple words separated via space character.
     */
    public function __construct(string $layerValue)
    {
        $this->layerName = "morphologicalAnalysis";
        $this->setLayerValue($layerValue);
    }

    /**
     * Sets the layer value to the string form of the given morphological parse.
     * @param mixed $layerValue New morphological parse.
     */
    function setLayerValue(mixed $layerValue): void
    {
        if ($layerValue instanceof MorphologicalParse) {
            $parse = $layerValue;
            $this->layerValue = $parse->getMorphologicalParseTransitionList();
            $this->items = [];
            $this->items[] = $parse;
        } else {
            $this->items[] = [];
            $this->layerValue = $layerValue;
            if ($layerValue != null) {
                $splitWords = explode(" ", $layerValue);
                foreach ($splitWords as $splitWord) {
                    $this->items[] = new MorphologicalParse($splitWord);
                }
            }
        }
    }

    /**
     * Returns the total number of morphological tags (for PART_OF_SPEECH) or inflectional groups
     * (for INFLECTIONAL_GROUP) in the words in the node.
     * @param ViewLayerType $viewLayer Layer type.
     * @return int Total number of morphological tags (for PART_OF_SPEECH) or inflectional groups (for INFLECTIONAL_GROUP)
     * in the words in the node.
     */
    function getLayerSize(ViewLayerType $viewLayer): int
    {
        switch ($viewLayer){
            case ViewLayerType::PART_OF_SPEECH:
                $size = 0;
                /** @var MorphologicalParse $parse */
                foreach ($this->items as $parse){
                    $size += $parse->tagSize();
                }
                return $size;
            case ViewLayerType::INFLECTIONAL_GROUP:
                $size = 0;
                /** @var MorphologicalParse $parse */
                foreach ($this->items as $parse){
                    $size += $parse->size();
                }
                return $size;
            default:
                return 0;
        }
    }

    /**
     * Returns the morphological tag (for PART_OF_SPEECH) or inflectional group (for INFLECTIONAL_GROUP) at position
     * index.
     * @param ViewLayerType $viewLayer Layer type.
     * @param int $index Position of the morphological tag (for PART_OF_SPEECH) or inflectional group (for INFLECTIONAL_GROUP)
     * @return string|null The morphological tag (for PART_OF_SPEECH) or inflectional group (for INFLECTIONAL_GROUP)
     */
    function getLayerInfoAt(ViewLayerType $viewLayer, int $index): ?string
    {
        switch ($viewLayer){
            case ViewLayerType::PART_OF_SPEECH:
                $size = 0;
                /** @var MorphologicalParse $parse */
                foreach ($this->items as $parse){
                    if ($index < $size + $parse->tagSize()){
                        return $parse->getTag($index - $size);
                    }
                    $size += $parse->tagSize();
                }
                return null;
            case ViewLayerType::INFLECTIONAL_GROUP:
                $size = 0;
                /** @var MorphologicalParse $parse */
                foreach ($this->items as $parse){
                    if ($index < $size + $parse->size()){
                        return $parse->getInflectionalGroupString($index - $size);
                    }
                    $size += $parse->size();
                }
                return null;
        }
        return null;
    }
}