<?php

namespace olcaytaner\AnnotatedTree\Processor\LayerExist;

use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\AnnotatedTree\Processor\Condition\IsTurkishLeafNode;
use olcaytaner\AnnotatedTree\Processor\LayerExist\LeafListCondition;

class ContainsLeayerInformation implements LeafListCondition
{
    private ViewLayerType $viewLayerType;

    /**
     * Constructor for ContainsLayerInformation class. Sets the viewLayerType attribute.
     * @param ViewLayerType $viewLayerType Layer for which check is done.
     */
    public function __construct(ViewLayerType $viewLayerType)
    {
        $this->viewLayerType = $viewLayerType;
    }

    /**
     * Checks if all leaf nodes in the leafList contains the given layer information.
     * @param array $leafList Array list storing the leaf nodes.
     * @return bool True if all leaf nodes in the leafList contains the given layer information, false otherwise.
     */
    function satisfies(array $leafList): bool
    {
        foreach ($leafList as $parseNode) {
            if (!str_contains($parseNode->getLayerData(ViewLayerType::ENGLISH_WORD), "*")) {
                switch ($this->viewLayerType) {
                    case ViewLayerType::TURKISH_WORD:
                        if ($parseNode->getLayerData($this->viewLayerType) == null) {
                            return false;
                        }
                        break;
                    case ViewLayerType::PART_OF_SPEECH:
                    case ViewLayerType::INFLECTIONAL_GROUP:
                    case ViewLayerType::NER:
                    case ViewLayerType::SEMANTICS:
                    case ViewLayerType::PROPBANK:
                        if ($parseNode->getLayerData($this->viewLayerType) == null && new IsTurkishLeafNode()->satisfies($parseNode)) {
                            return false;
                        }
                        break;
                }
            }
        }
        return true;
    }
}