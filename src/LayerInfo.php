<?php

namespace olcaytaner\AnnotatedTree;

use olcaytaner\AnnotatedSentence\AnnotatedWord;
use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\AnnotatedTree\Layer\DependencyLayer;
use olcaytaner\AnnotatedTree\Layer\EnglishPropbankLayer;
use olcaytaner\AnnotatedTree\Layer\EnglishSemanticLayer;
use olcaytaner\AnnotatedTree\Layer\EnglishWordLayer;
use olcaytaner\AnnotatedTree\Layer\MetaMorphemeLayer;
use olcaytaner\AnnotatedTree\Layer\MetaMorphemesMovedLayer;
use olcaytaner\AnnotatedTree\Layer\MorphologicalAnalysisLayer;
use olcaytaner\AnnotatedTree\Layer\MultiWordLayer;
use olcaytaner\AnnotatedTree\Layer\MultiWordMultiItemLayer;
use olcaytaner\AnnotatedTree\Layer\NERLayer;
use olcaytaner\AnnotatedTree\Layer\PersianWordLayer;
use olcaytaner\AnnotatedTree\Layer\ShallowParseLayer;
use olcaytaner\AnnotatedTree\Layer\SingleWordMultiItemLayer;
use olcaytaner\AnnotatedTree\Layer\TurkishPropbankLayer;
use olcaytaner\AnnotatedTree\Layer\TurkishSemanticLayer;
use olcaytaner\AnnotatedTree\Layer\TurkishWordLayer;
use olcaytaner\MorphologicalAnalysis\MorphologicalAnalysis\MetamorphicParse;
use olcaytaner\MorphologicalAnalysis\MorphologicalAnalysis\MorphologicalParse;
use olcaytaner\Propbank\Argument;

class LayerInfo
{
    private array $layers = [];

    public function __construct(?string $info = null)
    {
        $splitLayers = preg_split('/[{}]/', $info, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($splitLayers as $layer) {
            $layerType = mb_substr($layer, 0, mb_strpos($layer, '='));
            $layerValue = mb_substr($layer, mb_strpos($layer, '=') + 1);
            switch ($layerType) {
                case 'turkish':
                    $this->layers[ViewLayerType::TURKISH_WORD->value] = new TurkishWordLayer($layerValue);
                    break;
                case 'persian':
                    $this->layers[ViewLayerType::PERSIAN_WORD->value] = new PersianWordLayer($layerValue);
                    break;
                case 'english':
                    $this->layers[ViewLayerType::ENGLISH_WORD->value] = new EnglishWordLayer($layerValue);
                    break;
                case "morphologicalAnalysis":
                    $this->layers[ViewLayerType::INFLECTIONAL_GROUP->value] = new MorphologicalAnalysisLayer($layerValue);
                    $this->layers[ViewLayerType::PART_OF_SPEECH->value] = new MorphologicalAnalysisLayer($layerValue);
                    break;
                case "metaMorphemes":
                    $this->layers[ViewLayerType::META_MORPHEME->value] = new MetaMorphemeLayer($layerValue);
                    break;
                case "metaMorphemesMoved":
                    $this->layers[ViewLayerType::META_MORPHEME_MOVED->value] = new MetaMorphemesMovedLayer($layerValue);
                    break;
                case "dependency":
                    $this->layers[ViewLayerType::DEPENDENCY->value] = new DependencyLayer($layerValue);
                    break;
                case "semantics":
                    $this->layers[ViewLayerType::SEMANTICS->value] = new TurkishSemanticLayer($layerValue);
                    break;
                case "namedEntity":
                    $this->layers[ViewLayerType::NER->value] = new NerLayer($layerValue);
                    break;
                case "propBank":
                    $this->layers[ViewLayerType::PROPBANK->value] = new TurkishPropbankLayer($layerValue);
                    break;
                case "englishPropbank":
                    $this->layers[ViewLayerType::ENGLISH_PROPBANK->value] = new EnglishPropbankLayer($layerValue);
                    break;
                case "englishSemantics":
                    $this->layers[ViewLayerType::ENGLISH_SEMANTICS->value] = new EnglishSemanticLayer($layerValue);
                    break;
                case "shallowParse":
                    $this->layers[ViewLayerType::SHALLOW_PARSE->value] = new ShallowParseLayer($layerValue);
                    break;
            }
        }
    }

    /**
     * Changes the given layer info with the given string layer value. For all layers new layer object is created and
     * replaces the original object. For turkish layer, it also destroys inflectional_group, part_of_speech,
     * meta_morpheme, meta_morpheme_moved and semantics layers. For persian layer, it also destroys the semantics layer.
     * @param ViewLayerType $viewLayer Layer name.
     * @param string $layerValue New layer value.
     */
    public function setLayerData(ViewLayerType $viewLayer, string $layerValue): void
    {
        switch ($layerValue) {
            case ViewLayerType::PERSIAN_WORD:
                $this->layers[ViewLayerType::PERSIAN_WORD->value] = new PersianWordLayer($layerValue);
                unset($this->layers[ViewLayerType::SEMANTICS->value]);
                break;
            case ViewLayerType::TURKISH_WORD:
                $this->layers[ViewLayerType::TURKISH_WORD->value] = new TurkishWordLayer($layerValue);
                unset($this->layers[ViewLayerType::INFLECTIONAL_GROUP->value]);
                unset($this->layers[ViewLayerType::PART_OF_SPEECH->value]);
                unset($this->layers[ViewLayerType::META_MORPHEME->value]);
                unset($this->layers[ViewLayerType::META_MORPHEME_MOVED->value]);
                unset($this->layers[ViewLayerType::SEMANTICS->value]);
                break;
            case ViewLayerType::ENGLISH_WORD:
                $this->layers[ViewLayerType::ENGLISH_WORD->value] = new EnglishWordLayer($layerValue);
                break;
            case ViewLayerType::PART_OF_SPEECH:
            case ViewLayerType::INFLECTIONAL_GROUP:
                $this->layers[ViewLayerType::INFLECTIONAL_GROUP->value] = new MorphologicalAnalysisLayer($layerValue);
                $this->layers[ViewLayerType::PART_OF_SPEECH->value] = new MorphologicalAnalysisLayer($layerValue);
                unset($this->layers[ViewLayerType::META_MORPHEME_MOVED->value]);
                break;
            case ViewLayerType::META_MORPHEME:
                $this->layers[ViewLayerType::META_MORPHEME->value] = new MetaMorphemeLayer($layerValue);
                break;
            case ViewLayerType::META_MORPHEME_MOVED:
                $this->layers[ViewLayerType::META_MORPHEME_MOVED->value] = new MetaMorphemesMovedLayer($layerValue);
                break;
            case ViewLayerType::DEPENDENCY:
                $this->layers[ViewLayerType::DEPENDENCY->value] = new DependencyLayer($layerValue);
                break;
            case ViewLayerType::SEMANTICS:
                $this->layers[ViewLayerType::SEMANTICS->value] = new TurkishSemanticLayer($layerValue);
                break;
            case ViewLayerType::ENGLISH_SEMANTICS:
                $this->layers[ViewLayerType::ENGLISH_SEMANTICS->value] = new EnglishSemanticLayer($layerValue);
                break;
            case ViewLayerType::NER:
                $this->layers[ViewLayerType::NER->value] = new NerLayer($layerValue);
                break;
            case ViewLayerType::PROPBANK:
                $this->layers[ViewLayerType::PROPBANK->value] = new TurkishPropbankLayer($layerValue);
                break;
            case ViewLayerType::ENGLISH_PROPBANK:
                $this->layers[ViewLayerType::ENGLISH_PROPBANK->value] = new EnglishPropbankLayer($layerValue);
                break;
            case ViewLayerType::SHALLOW_PARSE:
                $this->layers[ViewLayerType::SHALLOW_PARSE->value] = new ShallowParseLayer($layerValue);
                break;
        }
    }

    /**
     * Updates the inflectional_group and part_of_speech layers according to the given parse.
     * @param MorphologicalParse $parse New parse to update layers.
     */
    public function setMorphologicalAnalysis(MorphologicalParse $parse): void
    {
        $this->layers[ViewLayerType::INFLECTIONAL_GROUP->value] = new MorphologicalAnalysisLayer($parse->__toString());
        $this->layers[ViewLayerType::PART_OF_SPEECH->value] = new MorphologicalAnalysisLayer($parse->__toString());
    }

    /**
     * Updates the metamorpheme layer according to the given parse.
     * @param MetamorphicParse $parse NEw parse to update layer.
     */
    public function setMetaMorphemes(MetamorphicParse $parse): void
    {
        $this->layers[ViewLayerType::META_MORPHEME->value] = new MetaMorphemeLayer($parse->__toString());
    }

    /**
     * Checks if the given layer exists.
     * @param ViewLayerType $viewLayerType Layer name
     * @return True if the layer exists, false otherwise.
     */
    public function layerExists(ViewLayerType $viewLayerType): bool
    {
        return array_key_exists($viewLayerType->value, $this->layers);
    }

    /**
     * Two level layer check method. For turkish, persian and english_semantics layers, if the layer does not exist,
     * returns english layer. For part_of_speech, inflectional_group, meta_morpheme, semantics, propbank, shallow_parse,
     * english_propbank layers, if the layer does not exist, it checks turkish layer. For meta_morpheme_moved, if the
     * layer does not exist, it checks meta_morpheme layer.
     * @param ViewLayerType $viewLayer Layer to be checked.
     * @return ViewLayerType Returns the original layer if the layer exists. For turkish, persian and english_semantics layers, if the
     * layer  does not exist, returns english layer. For part_of_speech, inflectional_group, meta_morpheme, semantics,
     * propbank,  shallow_parse, english_propbank layers, if the layer does not exist, it checks turkish layer
     * recursively. For meta_morpheme_moved, if the layer does not exist, it checks meta_morpheme layer recursively.
     */
    public function checkLayer(ViewLayerType $viewLayer): ViewLayerType
    {
        switch ($viewLayer) {
            case ViewLayerType::TURKISH_WORD:
            case ViewLayerType::PERSIAN_WORD:
            case ViewLayerType::ENGLISH_SEMANTICS:
                if (!array_key_exists($viewLayer->value, $this->layers)) {
                    return ViewLayerType::ENGLISH_WORD;
                }
            case ViewLayerType::PART_OF_SPEECH:
            case ViewLayerType::INFLECTIONAL_GROUP:
            case ViewLayerType::META_MORPHEME:
            case ViewLayerType::SEMANTICS:
            case ViewLayerType::NER:
            case ViewLayerType::PROPBANK:
            case ViewLayerType::SHALLOW_PARSE:
            case ViewLayerType::ENGLISH_PROPBANK:
                if (!array_key_exists($viewLayer->value, $this->layers)) {
                    return ViewLayerType::TURKISH_WORD;
                }
                break;
            case ViewLayerType::META_MORPHEME_MOVED:
                if (!array_key_exists($viewLayer->value, $this->layers)) {
                    return ViewLayerType::META_MORPHEME;
                }
                break;
        }
        return $viewLayer;
    }

    /**
     * Returns number of words in the Turkish or Persian layer, whichever exists.
     * @return int Number of words in the Turkish or Persian layer, whichever exists.
     */
    public function getNumberOfWords(): int
    {
        if (array_key_exists(ViewLayerType::TURKISH_WORD->value, $this->layers) && $this->layers[ViewLayerType::TURKISH_WORD->value] instanceof TurkishWordLayer) {
            return $this->layers[ViewLayerType::TURKISH_WORD->value]->size();
        } else {
            if (array_key_exists(ViewLayerType::PERSIAN_WORD->value, $this->layers) && $this->layers[ViewLayerType::PERSIAN_WORD->value] instanceof PERsianWordLayer) {
                return $this->layers[ViewLayerType::PERSIAN_WORD->value]->size();
            }
        }
        return 0;
    }

    /**
     * Returns the layer value at the given index.
     * @param ViewLayerType $viewLayerType Layer for which the value at the given word index will be returned.
     * @param int $index Word Position of the layer value.
     * @param string $layerName Name of the layer.
     * @return string|null Layer info at word position index for a multiword layer.
     */
    private function getMultiWordAt(ViewLayerType $viewLayerType, int $index, string $layerName): ?string
    {
        if (array_key_exists($viewLayerType->value, $this->layers)) {
            if ($this->layers[$viewLayerType->value] instanceof MultiWordLayer) {
                $multiWordLayer = $this->layers[$viewLayerType->value];
                if ($index < $multiWordLayer->size() && $index >= 0) {
                    return $multiWordLayer->getItemAt($index);
                } else {
                    if ($viewLayerType == ViewLayerType::SEMANTICS) {
                        return $multiWordLayer->getItemAt($multiWordLayer->size() - 1);
                    }
                }
            }
        }
        return null;
    }

    /**
     * Layers may contain multiple Turkish words. This method returns the Turkish word at position index.
     * @param int $index Position of the Turkish word.
     * @return string|null The Turkish word at position index.
     */
    public function getTurkishWordAt(int $index): ?string
    {
        return $this->getMultiWordAt(ViewLayerType::TURKISH_WORD, $index, "turkish");
    }

    /**
     * Returns number of meanings in the Turkish layer.
     * @return int Number of meanings in the Turkish layer.
     */
    public function getNumberOfMeanings(): int
    {
        if (array_key_exists(ViewLayerType::SEMANTICS->value, $this->layers) && $this->layers[ViewLayerType::SEMANTICS->value] instanceof TurkishSemanticLayer) {
            return $this->layers[ViewLayerType::SEMANTICS->value]->size();
        } else {
            return 0;
        }
    }

    /**
     * Layers may contain multiple semantic information corresponding to multiple Turkish words. This method returns
     * the sense id at position index.
     * @param int $index Position of the Turkish word.
     * @return string|null The Turkish sense id at position index.
     */
    public function getSemanticAt(int $index): ?string
    {
        return $this->getMultiWordAt(ViewLayerType::SEMANTICS, $index, "semantics");
    }

    /**
     * Layers may contain multiple shallow parse information corresponding to multiple Turkish words. This method
     * returns the shallow parse tag at position index.
     * @param int $index Position of the Turkish word.
     * @return string|null The shallow parse tag at position index.
     */
    public function getShallowParseAt(int $index): ?string
    {
        return $this->getMultiWordAt(ViewLayerType::SHALLOW_PARSE, $index, "shallowParse");
    }

    /**
     * Returns the Turkish PropBank argument info.
     * @return Argument|null Turkish PropBank argument info.
     */
    public function getArgument(): ?Argument
    {
        if (array_key_exists(ViewLayerType::PROPBANK->value, $this->layers)) {
            $argumentLayer = $this->layers[ViewLayerType::PROPBANK->value];
            if ($argumentLayer instanceof TurkishPropbankLayer) {
                return $argumentLayer->getArgument();
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * A word may have multiple English propbank info. This method returns the English PropBank argument info at
     * position index.
     * @return Argument|null English PropBank argument info at position index.
     */
    public function getArgumentAt(int $index): ?Argument
    {
        if (array_key_exists(ViewLayerType::ENGLISH_PROPBANK->value, $this->layers)) {
            $multiArgumentLayer = $this->layers[ViewLayerType::ENGLISH_PROPBANK->value];
            if ($multiArgumentLayer instanceof SingleWordMultiItemLayer) {
                return $multiArgumentLayer->getItemAt($index);
            }
        }
        return null;
    }

    /**
     * Layers may contain multiple morphological parse information corresponding to multiple Turkish words. This method
     * returns the morphological parse at position index.
     * @param int $index Position of the Turkish word.
     * @return MorphologicalParse|null The morphological parse at position index.
     */
    public function getMorphologicaParseAt(int $index): ?MorphologicalParse
    {
        if (array_key_exists(ViewLayerType::INFLECTIONAL_GROUP->value, $this->layers)) {
            if ($this->layers[ViewLayerType::INFLECTIONAL_GROUP->value] instanceof MultiWordLayer) {
                $multiWordLayer = $this->layers[ViewLayerType::INFLECTIONAL_GROUP->value];
                if ($index < $multiWordLayer->size() && $index >= 0) {
                    return $multiWordLayer->getItemAt($index);
                }
            }
        }
        return null;
    }

    /**
     * Layers may contain multiple metamorphic parse information corresponding to multiple Turkish words. This method
     * returns the metamorphic parse at position index.
     * @param int $index Position of the Turkish word.
     * @return MetamorphicParse|null The metamorphic parse at position index.
     */
    public function getMetamorphicParseAt(int $index): ?MetamorphicParse
    {
        if (array_key_exists(ViewLayerType::META_MORPHEME->value, $this->layers)) {
            if ($this->layers[ViewLayerType::META_MORPHEME->value] instanceof MultiWordLayer) {
                $multiWordLayer = $this->layers[ViewLayerType::META_MORPHEME->value];
                if ($index < $multiWordLayer->size() && $index >= 0) {
                    return $multiWordLayer->getItemAt($index);
                }
            }
        }
        return null;
    }

    /**
     * Layers may contain multiple metamorphemes corresponding to one or multiple Turkish words. This method
     * returns the metamorpheme at position index.
     * @param int $index Position of the metamorpheme.
     * @return string|null The metamorpheme at position index.
     */
    public function getMetaMorphemeAtIndex(int $index): ?string
    {
        if (array_key_exists(ViewLayerType::META_MORPHEME->value, $this->layers)) {
            if ($this->layers[ViewLayerType::META_MORPHEME->value] instanceof MetaMorphemeLayer) {
                $metaMorphemeLayer = $this->layers[ViewLayerType::META_MORPHEME->value];
                if ($index < $metaMorphemeLayer->getLayerSize(ViewLayerType::META_MORPHEME) && $index >= 0) {
                    return $metaMorphemeLayer->getLayerInfoAt(ViewLayerType::META_MORPHEME, $index);
                }
            }
        }
        return null;
    }

    /**
     * Layers may contain multiple metamorphemes corresponding to one or multiple Turkish words. This method
     * returns all metamorphemes from position index.
     * @param int $index Start position of the metamorpheme.
     * @return string|null All metamorphemes from position index.
     */
    public function getMetaMorphemeFromIndex(int $index): ?string
    {
        if (array_key_exists(ViewLayerType::META_MORPHEME->value, $this->layers)) {
            if ($this->layers[ViewLayerType::META_MORPHEME->value] instanceof MetaMorphemeLayer) {
                $metaMorphemeLayer = $this->layers[ViewLayerType::META_MORPHEME->value];
                if ($index < $metaMorphemeLayer->getLayerSize(ViewLayerType::META_MORPHEME) && $index >= 0) {
                    return $metaMorphemeLayer->getLayerInfoFrom($index);
                }
            }
        }
        return null;
    }

    /**
     * For layers with multiple item information, this method returns total items in that layer.
     * @param ViewLayerType $viewLayer Layer name
     * @return int Total items in the given layer.
     */
    public function getLayerSize(ViewLayerType $viewLayer): int
    {
        if ($this->layers[$viewLayer->value] instanceof MultiWordMultiItemLayer) {
            $layer = $this->layers[$viewLayer->value];
            return $layer->getLayerSize($viewLayer);
        } else {
            if ($this->layers[$viewLayer->value] instanceof SingleWordMultiItemLayer) {
                $layer = $this->layers[$viewLayer->value];
                return $layer->getLayerSize($viewLayer);
            }
        }
        return 0;
    }

    /**
     * For layers with multiple item information, this method returns the item at position index.
     * @param ViewLayerType $viewLayer Layer name
     * @param int $index Position of the item.
     * @return string|null The item at position index.
     */
    public function getLayerInfoAt(ViewLayerType $viewLayer, int $index): ?string
    {
        switch ($viewLayer) {
            case ViewLayerType::META_MORPHEME_MOVED:
            case ViewLayerType::PART_OF_SPEECH:
            case ViewLayerType::INFLECTIONAL_GROUP:
                if ($this->layers[$viewLayer->value] instanceof MultiWordMultiItemLayer) {
                    $layer = $this->layers[$viewLayer->value];
                    return $layer->getLayerInfoAt($viewLayer, $index);
                }
                break;
            case ViewLayerType::META_MORPHEME:
                return $this->getMetaMorphemeAtIndex($index);
            case ViewLayerType::ENGLISH_PROPBANK:
                return $this->getArgumentAt($index)->getArgumentType();
            default:
                return null;
        }
        return null;
    }

    /**
     * Returns the string form of all layer information except part_of_speech layer.
     * @return string The string form of all layer information except part_of_speech layer.
     */
    public function getLayerDescription(): string{
        $result = "";
        foreach ($this->layers as $key => $layer) {
            if ($key != ViewLayerType::PART_OF_SPEECH->value) {
                $result .= $this->layers[$key]->getLayerDescription();
            }
        }
        return $result;
    }

    /**
     * Returns the layer info for the given layer.
     * @param ViewLayerType $viewLayer Layer name.
     * @return string|null Layer info for the given layer.
     */
    public function getLayerData(ViewLayerType $viewLayer): ?string{
        if (array_key_exists($viewLayer->value, $this->layers)){
            return $this->layers[$viewLayer->value]->getLayerValue();
        } else {
            return null;
        }
    }

    /**
     * Returns the layer info for the given layer, if that layer exists. Otherwise, it returns the fallback layer info
     * determined by the checkLayer.
     * @param ViewLayerType $viewLayer Layer name
     * @return string|null Layer info for the given layer if it exists. Otherwise, it returns the fallback layer info determined by
     * the checkLayer.
     */
    public function getRobustLayerData(ViewLayerType $viewLayer): ?string{
        $viewLayer = $this->checkLayer($viewLayer);
        return $this->getLayerData($viewLayer);
    }

    /**
     * Initializes the metamorphemesmoved layer with metamorpheme layer except the root word.
     */
    public function updateMetaMorphemesMoved(): void{
        if (array_key_exists(ViewLayerType::META_MORPHEME->value, $this->layers)){
            $metaMorphemeLayer = $this->layers[ViewLayerType::META_MORPHEME->value];
            if ($metaMorphemeLayer instanceof MetaMorphemeLayer && $metaMorphemeLayer->size() > 0) {
                $result = $metaMorphemeLayer->getItemAt(0)->__toString();
                for ($i = 1; $i < $metaMorphemeLayer->size(); $i++) {
                    $result .= " " . $metaMorphemeLayer->getItemAt($i)->__toString();
                }
                $this->layers[ViewLayerType::META_MORPHEME_MOVED->value] = new MetaMorphemesMovedLayer($result);
            }
        }
    }

    /**
     * Removes the given layer from hash map.
     * @param ViewLayerType $layerType Layer to be removed.
     */
    public function removeLayer(ViewLayerType $layerType): void{
        unset($this->layers[$layerType->value]);
    }

    /**
     * Removes metamorpheme and metamorphemesmoved layers.
     */
    public function metaMorphemeClear(): void{
        unset($this->layers[ViewLayerType::META_MORPHEME->value]);
        unset($this->layers[ViewLayerType::META_MORPHEME_MOVED->value]);
    }

    /**
     * Removes English layer.
     */
    public function englishClear(): void{
        unset($this->layers[ViewLayerType::ENGLISH_WORD->value]);
    }

    /**
     * Removes the dependency layer.
     */
    public function dependencyClear(): void{
        unset($this->layers[ViewLayerType::DEPENDENCY->value]);
    }

    /**
     * Removed metamorphemesmoved layer.
     */
    public function metaMorphemesMovedClear(): void{
        unset($this->layers[ViewLayerType::META_MORPHEME_MOVED->value]);
    }

    /**
     * Removes the Turkish semantic layer.
     */
    public function semanticClear(): void{
        unset($this->layers[ViewLayerType::SEMANTICS->value]);
    }

    /**
     * Removes the English semantic layer.
     */
    public function englishSemanticClear(): void{
        unset($this->layers[ViewLayerType::ENGLISH_SEMANTICS->value]);
    }

    /**
     * Removes the morphological analysis, part of speech, metamorpheme, and metamorphemesmoved layers.
     */
    public function morphologicalAnalysisClear(): void{
        unset($this->layers[ViewLayerType::INFLECTIONAL_GROUP->value]);
        unset($this->layers[ViewLayerType::PART_OF_SPEECH->value]);
        unset($this->layers[ViewLayerType::META_MORPHEME->value]);
        unset($this->layers[ViewLayerType::META_MORPHEME_MOVED->value]);
    }

    /**
     * Removes the metamorpheme at position index.
     * @param int $index Position of the metamorpheme to be removed.
     * @return MetamorphicParse|null Metamorphemes concatenated as a string after the removed metamorpheme.
     */
    public function metaMorphemeRemove(int $index): ?MetamorphicParse{
        $removeParse = null;
        if (array_key_exists(ViewLayerType::META_MORPHEME->value, $this->layers)){
            $metaMorphemeLayer = $this->layers[ViewLayerType::META_MORPHEME->value];
            if ($index >= 0 && $index < $metaMorphemeLayer->getLayerSize(ViewLayerType::META_MORPHEME) && $metaMorphemeLayer instanceof MetaMorphemeLayer) {
                $removeParse = $metaMorphemeLayer->metaMorphemeRemoveFromIndex($index);
                $this->updateMetaMorphemesMoved();
            }
        }
        return $removeParse;
    }

    /**
     * Creates an array list of LayerInfo objects, where each object correspond to one word in the tree node. Turkish
     * words, morphological parses, metamorpheme parses, semantic senses, shallow parses are divided into corresponding
     * words. Named entity tags and propbank arguments are the same for all words.
     * @return array An array list of LayerInfo objects created from the layer info of the node.
     */
    public function divideIntoWords(): array{
        $result = [];
        for ($i = 0; $i < $this->getNumberOfWords(); $i++) {
            $layerInfo = new LayerInfo();
            $layerInfo->setLayerData(ViewLayerType::TURKISH_WORD, $this->getTurkishWordAt($i));
            $layerInfo->setLayerData(ViewLayerType::ENGLISH_WORD, $this->getLayerData(ViewLayerType::ENGLISH_WORD));
            if ($this->layerExists(ViewLayerType::INFLECTIONAL_GROUP)){
                $layerInfo->setMorphologicalAnalysis($this->getMorphologicaParseAt($i));
            }
            if ($this->layerExists(ViewLayerType::META_MORPHEME)){
                $layerInfo->setMetaMorphemes($this->getMetamorphicParseAt($i));
            }
            if ($this->layerExists(ViewLayerType::ENGLISH_PROPBANK)){
                $layerInfo->setLayerData(ViewLayerType::ENGLISH_PROPBANK, $this->getLayerData(ViewLayerType::ENGLISH_PROPBANK));
            }
            if ($this->layerExists(ViewLayerType::ENGLISH_SEMANTICS)){
                $layerInfo->setLayerData(ViewLayerType::ENGLISH_SEMANTICS, $this->getLayerData(ViewLayerType::ENGLISH_SEMANTICS));
            }
            if ($this->layerExists(ViewLayerType::NER)){
                $layerInfo->setLayerData(ViewLayerType::NER, $this->getLayerData(ViewLayerType::NER));
            }
            if ($this->layerExists(ViewLayerType::SEMANTICS)){
                $layerInfo->setLayerData(ViewLayerType::SEMANTICS, $this->getSemanticAt($i));
            }
            if ($this->layerExists(ViewLayerType::PROPBANK)){
                $layerInfo->setLayerData(ViewLayerType::PROPBANK, $this->getArgument()->__toString());
            }
            if ($this->layerExists(ViewLayerType::SHALLOW_PARSE)){
                $layerInfo->setLayerData(ViewLayerType::SHALLOW_PARSE, $this->getShallowParseAt($i));
            }
            $result[] = $layerInfo;
        }
        return $result;
    }

    /**
     * Converts layer info of the word at position wordIndex to an AnnotatedWord. Layers are converted to their
     * counterparts in the AnnotatedWord.
     * @param int $wordIndex Index of the word to be converted.
     * @return AnnotatedWord Converted annotatedWord
     */
    public function toAnnotatedWord(int $wordIndex): AnnotatedWord{
        $annotatedWord = new AnnotatedWord($this->getTurkishWordAt($wordIndex));
        if ($this->layerExists(ViewLayerType::INFLECTIONAL_GROUP)){
            $annotatedWord->setParse($this->getMorphologicaParseAt($wordIndex)->__toString());
        }
        if ($this->layerExists(ViewLayerType::META_MORPHEME)){
            $annotatedWord->setMetamorphicParse($this->getMetamorphicParseAt($wordIndex)->__toString());
        }
        if ($this->layerExists(ViewLayerType::SEMANTICS)){
            $annotatedWord->setSemantic($this->getSemanticAt($wordIndex));
        }
        if ($this->layerExists(ViewLayerType::NER)){
            $annotatedWord->setNamedEntityType($this->getLayerData(ViewLayerType::NER));
        }
        if ($this->layerExists(ViewLayerType::PROPBANK)){
            $annotatedWord->setArgumentList($this->getArgument()->__toString());
        }
        if ($this->layerExists(ViewLayerType::SHALLOW_PARSE)){
            $annotatedWord->setShallowParse($this->getShallowParseAt($wordIndex));
        }
        return $annotatedWord;
    }

}