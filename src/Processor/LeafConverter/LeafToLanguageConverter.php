<?php

namespace olcaytaner\AnnotatedTree\Processor\LeafConverter;

use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\AnnotatedTree\ParseNodeDrawable;
use olcaytaner\AnnotatedTree\Processor\LeafConverter\LeafToStringConverter;

class LeafToLanguageConverter implements LeafToStringConverter
{

    protected ViewLayerType $viewLayerType;

    /**
     * Converts the data in the leaf node to string, except shortcuts to parentheses are converted to its normal forms,
     * '*', '0', '-NONE-' are converted to empty string.
     * @param ParseNodeDrawable $leafNode Node to be converted to string.
     * @return string String form of the data, except shortcuts to parentheses are converted to its normal forms,
     * '*', '0', '-NONE-' are converted to empty string.
     */
    public function leafConverter(ParseNodeDrawable $leafNode): string
    {
        $layerData = $leafNode->getLayerData($this->viewLayerType);
        $parentLayerData = $leafNode->getParent()->getLayerData($this->viewLayerType);
        if ($layerData != null) {
            if (str_contains($layerData, "*") || ($layerData == "0" && $parentLayerData == "-NONE-")) {
                return "";
            } else {
                return str_replace(["-LRB-", "-RRB-", "-LSB-", "-RSB-", "-LCB-", "-RCB-", "-lrb-", "-rrb-", "-lsb-", "-rsb-", "-lcb-", "-rcb-"],
                    ["(", ")", "[", "]", "{", "}", "(", ")", "[", "]", "{", "}"], $layerData);
            }
        }
    }
}