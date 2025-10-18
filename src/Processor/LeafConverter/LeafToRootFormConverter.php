<?php

namespace olcaytaner\AnnotatedTree\Processor\LeafConverter;

use olcaytaner\AnnotatedTree\ParseNodeDrawable;

class LeafToRootFormConverter implements LeafToStringConverter
{

    /**
     * Converts the data in the leaf node to string. If there are multiple words in the leaf node, they are concatenated
     * with space.
     * @param ParseNodeDrawable $leafNode Node to be converted to string.
     * @return string String form of the data. If there are multiple words in the leaf node, they are concatenated
     * with space.
     */
    public function leafConverter(ParseNodeDrawable $leafNode): string
    {
        $layerInfo = $leafNode->getLayerInfo();
        $rootWords = "";
        for ($i = 0; $i < $layerInfo->getNumberOfWords(); $i++) {
            $root = $layerInfo->getMorphologicaParseAt($i)->getWord()->getName();
            if ($root != null && $root != "") {
                $rootWords .= " " . $root;
            }
        }
        return $rootWords;
    }
}