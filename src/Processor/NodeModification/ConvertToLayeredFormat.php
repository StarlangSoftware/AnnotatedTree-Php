<?php

namespace olcaytaner\AnnotatedTree\Processor\NodeModification;

use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\AnnotatedTree\ParseNodeDrawable;

class ConvertToLayeredFormat implements NodeModifier
{

    public function modifier(ParseNodeDrawable $parseNode): void
    {
        if ($parseNode->isLeaf()) {
            $name = $parseNode->getData()->getName();
            $parseNode->clearLayers();
            $parseNode->getLayerInfo()->setLayerData(ViewLayerType::ENGLISH_WORD, $name);
            $parseNode->clearData();
        }
    }
}