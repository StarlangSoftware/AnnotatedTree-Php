<?php

namespace olcaytaner\AnnotatedTree\Processor\LayerExist;

use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\AnnotatedTree\Processor\Condition\IsTurkishLeafNode;

class SemiContainsLayerInformation implements LeafListCondition
{

    private ViewLayerType $viewLayerType;

    /**
     * Constructor for SemiContainsLayerInformation class. Sets the viewLayerType attribute.
     * @param ViewLayerType $viewLayerType Layer for which check is done.
     */
    public function __construct(ViewLayerType $viewLayerType)
    {
        $this->viewLayerType = $viewLayerType;
    }

    /**
     * Checks if some (but not all) of the leaf nodes in the leafList contains the given layer information.
     * @param array $leafList Array list storing the leaf nodes.
     * @return bool True if some (but not all) of the leaf nodes in the leafList contains the given layer information, false
     */
    function satisfies(array $leafList): bool
    {
        $notDone = 0;
        $done = 0;
        foreach ($leafList as $parseNode) {
            if (!str_contains($parseNode->getLayerData(ViewLayerType::ENGLISH_WORD), "*")) {
                switch ($this->viewLayerType) {
                    case ViewLayerType::TURKISH_WORD:
                        if ($parseNode->getLayerData($this->viewLayerType) != null) {
                            $done++;
                        } else {
                            $notDone++;
                        }
                        break;
                    case ViewLayerType::PART_OF_SPEECH:
                    case ViewLayerType::INFLECTIONAL_GROUP:
                    case ViewLayerType::NER:
                    case ViewLayerType::SEMANTICS:
                    case ViewLayerType::PROPBANK:
                        if (new IsTurkishLeafNode()->satisfies($parseNode)){
                            if ($parseNode->getLayerData($this->viewLayerType) != null) {
                                $done++;
                            } else {
                                $notDone++;
                            }
                        }
                        break;
                }
            }
        }
        return $done != 0 && $notDone != 0;
    }}