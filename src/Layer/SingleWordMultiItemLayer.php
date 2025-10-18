<?php

namespace olcaytaner\AnnotatedTree\Layer;

use olcaytaner\AnnotatedSentence\ViewLayerType;

class SingleWordMultiItemLayer extends SingleWordLayer
{
    protected array $items = [];

    public function getItemAt(int $index): mixed{
        if ($index < count($this->items)) {
            return $this->items[$index];
        } else {
            return null;
        }
    }

    public function getLayerSize(ViewLayerType $viewLayer): int{
        return count($this->items);
    }
}