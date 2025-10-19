<?php

namespace olcaytaner\AnnotatedTree\Layer;


use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\MorphologicalAnalysis\MorphologicalAnalysis\MetamorphicParse;

class MetaMorphemeLayer extends MetaMorphemesMovedLayer
{
    /**
     * Constructor for the metamorpheme layer. Sets the metamorpheme information for multiple words in the node.
     * @param string $layerValue Layer value for the metamorpheme information. Consists of metamorpheme information of multiple
     *                   words separated via space character.
     */
    public function __construct(string $layerValue)
    {
        parent::__construct($layerValue);
        $this->layerName = "metaMorphemes";
    }

    /**
     * Sets the layer value to the string form of the given parse.
     * @param mixed $parse New metamorphic parse.
     */
    public function setLayerValue(mixed $parse): void
    {
        $this->layerValue = $parse;
        $this->items = [];
        if ($this->layerValue != null){
            $splitWords = explode(" ", $this->layerValue);
            foreach ($splitWords as $splitWord){
                $this->items[] = new MetamorphicParse($splitWord);
            }
        }
    }

    /**
     * Constructs metamorpheme information starting from the position index.
     * @param int $index Position of the morpheme to start.
     * @return string|null Metamorpheme information starting from the position index.
     */
    public function getLayerInfoFrom(int $index): ?string{
        $size = 0;
        /** @var MetamorphicParse $parse */
        foreach ($this->items as $parse){
            if ($index < $size + $parse->size()){
                $result = $parse->getMetaMorpheme($index - $size);
                $index++;
                while ($index < $size + $parse->size()){
                    $result .= "+" . $parse->getMetaMorpheme($index - $size);
                    $index++;
                }
                return $result;
            }
            $size += $parse->size();
        }
        return null;
    }

    /**
     * Removes metamorphemes from the given index. Index shows the position of the metamorpheme in the metamorphemes list.
     * @param int $index Position of the metamorpheme from which the other metamorphemes will be removed.
     * @return MetamorphicParse|null New metamorphic parse not containing the removed parts.
     */
    public function metaMorphemeRemoveFromIndex(int $index): ?MetamorphicParse{
        if ($index > 0 && $index < $this->getLayerSize(ViewLayerType::META_MORPHEME)){
            $size = 0;
            /** @var MetamorphicParse $parse */
            foreach ($this->items as $parse){
                if ($index < $size + $parse->size()){
                    $parse->removeMetaMorphemeFromIndex($index - $size);
                    return $parse;
                }
                $size += $parse->size();
            }
        }
        return null;
    }
}