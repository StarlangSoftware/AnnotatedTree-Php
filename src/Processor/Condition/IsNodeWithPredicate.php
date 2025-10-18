<?php

namespace olcaytaner\AnnotatedTree\Processor\Condition;

use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\AnnotatedTree\ParseNodeDrawable;

class IsNodeWithPredicate extends IsNodeWithSynSetId
{
    /**
     * Stores the synset id to check.
     * @param string $id Synset id to check
     */
    public function __construct(string $id){
        parent::__construct($id);
    }

    /**
     * Checks if at least one of the semantic ids of the parse node is equal to the given id and also the node is
     * annotated as PREDICATE with semantic role.
     * @param ParseNodeDrawable $parseNode Parse node to check.
     * @return bool True if at least one of the semantic ids of the parse node is equal to the given id and also the node is
     * annotated as PREDICATE with semantic role, false otherwise.
     */
    public function satisfies(ParseNodeDrawable $parseNode): bool{
        $layerInfo = $parseNode->getLayerInfo();
        return parent::satisfies($parseNode)
            && $layerInfo != null
            && $layerInfo->getLayerData(ViewLayerType::PROPBANK) != null
            && $layerInfo->getLayerData(ViewLayerType::PROPBANK) == "PREDICATE";
    }
}