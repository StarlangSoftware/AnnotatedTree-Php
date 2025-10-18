<?php

namespace olcaytaner\AnnotatedTree\Processor\Condition;

use olcaytaner\AnnotatedTree\ParseNodeDrawable;

class IsNodeWithSynSetId extends IsLeafNode
{
    private string $id;

    /**
     * Stores the synset id to check.
     * @param string $id Synset id to check
     */
    public function __construct(string $id){
        $this->id = $id;
    }

    /**
     * Checks if at least one of the semantic ids of the parse node is equal to the given id.
     * @param ParseNodeDrawable $parseNode Parse node to check.
     * @return bool True if at least one of the semantic ids of the parse node is equal to the given id, false otherwise.
     */
    public function satisfies(ParseNodeDrawable $parseNode): bool{
        if (parent::satisfies($parseNode)) {
            $layerInfo = $parseNode->getLayerInfo();
            for ($i = 0; $i < $layerInfo->getNumberOfMeanings(); $i++) {
                $synSetId = $layerInfo->getSemanticAt($i);
                if ($synSetId == $this->id) {
                    return true;
                }
            }
        }
        return false;
    }
}