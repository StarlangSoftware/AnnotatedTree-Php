<?php

namespace olcaytaner\AnnotatedTree\Layer;

abstract class MultiWordLayer extends WordLayer
{
    protected array $items = [];

    abstract function setLayerValue(string $layerValue): void;

    /**
     * Returns the item (word or its property) at position index.
     * @param int $index Position of the item (word or its property).
     * @return mixed The item at position index.
     */
    public function getItemAt(int $index): mixed{
        if ($index < count($this->items)) {
            return $this->items[$index];
        } else {
            return null;
        }
    }

    /**
     * Returns number of items (words) in the items array list.
     * @return int Number of items (words) in the items array list.
     */
    public function size(): int{
        return count($this->items);
    }
}