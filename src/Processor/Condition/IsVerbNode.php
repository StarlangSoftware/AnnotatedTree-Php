<?php

namespace olcaytaner\AnnotatedTree\Processor\Condition;

use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\AnnotatedTree\ParseNodeDrawable;
use olcaytaner\Dictionary\Dictionary\Pos;
use olcaytaner\WordNet\WordNet;

class IsVerbNode extends IsLeafNode
{
    private WordNet $wordNet;

    /**
     * Stores the wordnet for checking the pos tag of the synset.
     * @param WordNet $wordNet Wordnet used for checking the pos tag of the synset.
     */
    public function __construct(WordNet $wordNet){
        $this->wordNet = $wordNet;
    }

    /**
     * Checks if the node is a leaf node and at least one of the semantic ids of the parse node belong to a verb synset.
     * @param ParseNodeDrawable $parseNode Parse node to check.
     * @return bool True if the node is a leaf node and at least one of the semantic ids of the parse node belong to a verb
     * synset, false otherwise.
     */
    public function satisfies(ParseNodeDrawable $parseNode): bool
    {
        $layerInfo = $parseNode->getLayerInfo();
        if (parent::satisfies($parseNode) && $layerInfo != null && $layerInfo->getLayerData(ViewLayerType::SEMANTICS) != null) {
            for ($i = 0; $i < $layerInfo->getNumberOfMeanings(); $i++) {
                $synSetId = $layerInfo->getSemanticAt($i);
                if ($this->wordNet->getSynSetWithId($synSetId) != null && $this->wordNet->getSynSetWithId($synSetId)->getPos() == Pos::VERB) {
                    return true;
                }
            }
        }
        return false;
    }
}