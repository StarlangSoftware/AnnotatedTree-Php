<?php

namespace olcaytaner\AnnotatedTree\Layer;

use olcaytaner\AnnotatedSentence\ViewLayerType;

abstract class MultiWordMultiItemLayer extends MultiWordLayer
{
    abstract function getLayerSize(ViewLayerType $viewLayer): int;
    abstract function getLayerInfoAt(ViewLayerType $viewLayer, int $index): ?string;
}