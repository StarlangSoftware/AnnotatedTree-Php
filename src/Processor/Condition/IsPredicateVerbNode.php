<?php

namespace olcaytaner\AnnotatedTree\Processor\Condition;

use olcaytaner\AnnotatedTree\ParseNodeDrawable;
use olcaytaner\WordNet\WordNet;

class IsPredicateVerbNode extends IsVerbNode
{
    /**
     * Stores the wordnet for checking the pos tag of the synset.
     * @param WordNet $wordNet Wordnet used for checking the pos tag of the synset.
     */
    public function __construct(WordNet $wordNet){
        parent::__construct($wordNet);
    }

    /**
     * Checks if the node is a leaf node and at least one of the semantic ids of the parse node belong to a verb synset,
     * and the semantic role of the node is PREDICATE.
     * @param ParseNodeDrawable $parseNode Parse node to check.
     * @return bool True if the node is a leaf node and at least one of the semantic ids of the parse node belong to a verb
     *          synset and the semantic role of the node is PREDICATE, false otherwise.
     */
    public function satisfies(ParseNodeDrawable $parseNode): bool
    {
        $layerInfo = $parseNode->getLayerInfo();
        return parent::satisfies($parseNode)
            && $layerInfo != null
            && $layerInfo->getArgument() != null
            && $layerInfo->getArgument()->getArgumentType() == "PREDICATE";
    }
}