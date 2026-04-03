<?php

namespace olcaytaner\AnnotatedTree;

use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\NamedEntityRecognition\Gazetteer;
use olcaytaner\ParseTree\ParseNode;
use olcaytaner\ParseTree\Symbol;

class ParseNodeDrawable extends ParseNode
{
    protected ?LayerInfo $layers = null;
    protected int $depth;
    protected int $inOrderTraversalIndex;

    /**
     * Constructs a ParseNodeDrawable from a single line. If the node is a leaf node, it only sets the data. Otherwise,
     * splits the line w.r.t. spaces and parenthesis and calls itself recursively to generate its child parseNodes.
     * @param ParseNodeDrawable|Symbol|null $parentOrLeftOrSymbol The parent node of this node.
     * @param string|ParseNodeDrawable|Symbol|null $lineOrRightOrData The input line to create this parseNode.
     * @param Symbol|bool|null $dataOrIsLeaf True, if this node is a leaf node; false otherwise.
     * @param int|null $depth Depth of the node.
     */
    public function __construct(ParseNodeDrawable|Symbol|null $parentOrLeftOrSymbol = null, string|ParseNodeDrawable|Symbol|null $lineOrRightOrData = null, Symbol|bool|null $dataOrIsLeaf = null, int|null $depth = null)
    {
        parent::__construct();
        if ($parentOrLeftOrSymbol == null || $lineOrRightOrData != null) {
            $parent = $parentOrLeftOrSymbol;
            if ($depth !== null) {
                $line = $lineOrRightOrData;
                $isLeaf = $dataOrIsLeaf;
                $parenthesisCount = 0;
                $childLine = "";
                $this->depth = $depth;
                $this->parent = $parent;
                if ($isLeaf) {
                    if (!str_contains($line, "{")) {
                        $this->data = new Symbol($line);
                    } else {
                        $this->layers = new LayerInfo($line);
                    }
                } else {
                    $startPos = mb_strpos($line, " ");
                    $this->data = new Symbol(mb_substr($line, 1, $startPos - 1));
                    if (mb_strpos($line, ")") == mb_strrpos($line, ")")) {
                        $this->children[] = new ParseNodeDrawable($this, mb_substr($line, $startPos + 1, mb_strpos($line, ")") - $startPos - 1), true, $depth + 1);
                    } else {
                        for ($i = $startPos + 1; $i < mb_strlen($line); $i++) {
                            $ch = mb_substr($line, $i, 1);
                            if ($ch != " " || $parenthesisCount > 0) {
                                $childLine .= $ch;
                            }
                            if ($ch == "(") {
                                $parenthesisCount++;
                            } else {
                                if ($ch == ")") {
                                    $parenthesisCount--;
                                }
                            }
                            if ($parenthesisCount == 0 && $childLine != "") {
                                $this->children[] = new ParseNodeDrawable($this, trim($childLine), false, $depth + 1);
                                $childLine = "";
                            }
                        }
                    }
                }
            } else {
                $child = $lineOrRightOrData;
                $symbol = $dataOrIsLeaf;
                $this->depth = $child->depth;
                $child->updateDepths($this->depth + 1);
                $this->parent = $parent;
                $this->parent->setChild($parent->getChildIndex($child), $this);
                $this->children[] = $child;
                $child->parent = $this;
                $this->data = new Symbol($symbol);
                $this->inOrderTraversalIndex = $child->inOrderTraversalIndex;
            }
        }
    }

    /**
     * Accessor for layers attribute
     * @return LayerInfo|null Layers attribute
     */
    public function getLayerInfo(): ?LayerInfo
    {
        return $this->layers;
    }

    /**
     * Returns the data. Either the node is a leaf node, in which case English word layer is returned; or the node is
     * a nonleaf node, in which case the node tag is returned.
     * @return Symbol|null English word for leaf node, constituency tag for non-leaf node.
     */
    public function getData(): ?Symbol
    {
        if ($this->layers == null) {
            return parent::getData();
        } else {
            return new Symbol($this->getLayerData(ViewLayerType::ENGLISH_WORD));
        }
    }

    /**
     * Clears the layers hash map.
     */
    public function clearLayers(): void
    {
        $this->layers = new LayerInfo();
    }

    /**
     * Recursive method to clear a given layer.
     * @param ViewLayerType $layerType Name of the layer to be cleared
     */
    public function clearLayer(ViewLayerType $layerType): void
    {
        if (count($this->children) == 0 && $this->layerExists($layerType)) {
            $this->layers->removeLayer($layerType);
        }
        for ($i = 0; $i < count($this->children); $i++) {
            $this->children[$i]->clearLayer($layerType);
        }
    }

    /**
     * Clears the node tag.
     */
    public function clearData(): void
    {
        $this->data = null;
    }

    /**
     * Setter for the data attribute and also clears all layers.
     * @param Symbol $data New data field.
     */
    public function setDataAndClearLayers(Symbol $data): void
    {
        parent::setData($data);
        $this->layers = null;
    }

    /**
     * Mutator for the data field. If the layers is null, its sets the data field, otherwise it sets the English layer
     * to the given value.
     * @param Symbol|null $data Data to be set.
     */
    public function setData(Symbol|null $data): void
    {
        if ($this->layers == null) {
            parent::setData($data);
        } else {
            $this->layers->setLayerData(ViewLayerType::ENGLISH_WORD, $data->getName());
        }
    }

    /**
     * Returns the layer value of the head child of this node.
     * @param ViewLayerType $layerType Layer name
     * @return string Layer value of the head child of this node.
     */
    public function headWord(ViewLayerType $layerType): string
    {
        if (count($this->children) > 0) {
            return $this->headChild()->headWord($layerType);
        } else {
            return $this->getLayerData($layerType);
        }
    }

    /**
     * Returns the layer value of a given layer.
     * @param ViewLayerType|null $layerType Layer name
     * @return string Value of the given layer
     */
    public function getLayerData(ViewLayerType|null $layerType = null): string
    {
        if ($layerType == null) {
            if ($this->data != null) {
                return $this->data->getName();
            }
            return $this->layers->getLayerDescription();
        } else {
            if ($layerType == ViewLayerType::WORD || $this->layers == null) {
                return $this->data->getName();
            }
            return $this->layers->getLayerData($layerType);
        }
    }

    /**
     * Accessor for the depth attribute
     * @return int Depth attribute
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * Recursive setter method for the inOrderTraversalIndex attribute. InOrderTraversalIndex shows the index of the
     * node according to the inorder traversal.
     * @param int $pos Current inorder traversal index
     * @return int Update inorder traversal index
     */
    public function inOrderTraversal(int $pos): int{
        for ($i = 0; $i < count($this->children) / 2; $i++) {
            $pos = $this->children[$i]->inOrderTraversal($pos);
        }
        $this->inOrderTraversalIndex = $pos;
        if (count($this->children) % 2 != 1) {
            $pos++;
        }
        for ($i = count($this->children) / 2; $i < count($this->children); $i++) {
            $pos = $this->children[$i]->inOrderTraversal($pos);
        }
        return $pos;
    }

    /**
     * Returns the maximum inorder traversal index considering this node and all of its descendants.
     * @return int The maximum inorder traversal index considering this node and all of its descendants.
     */
    public function maxInOrderTraversal(): int
    {
        if (count($this->children) == 0) {
            return $this->inOrderTraversalIndex;
        } else {
            $maxIndex = $this->inOrderTraversalIndex;
            for ($i = 0; $i < count($this->children); $i++) {
                $child = $this->children[$i];
                $childIndex = $child->maxInOrderTraversal();
                if ($childIndex > $maxIndex) {
                    $maxIndex = $childIndex;
                }
            }
            return $maxIndex;
        }
    }

    /**
     * Replaces a given old child with the given new child.
     * @param ParseNodeDrawable $oldChild Old child to be replaced
     * @param ParseNodeDrawable $newChild New child which replaces old child
     */
    public function replaceChild(ParseNodeDrawable $oldChild, ParseNodeDrawable $newChild): void
    {
        $newChild->updateDepths($this->depth + 1);
        $newChild->parent = $this;
        $this->children[array_search($oldChild, $this->children)] = $newChild;
    }

    /**
     * Recursive method which updates the depth attribute
     * @param int $depth Current depth to set.
     */
    public function updateDepths(int $depth): void
    {
        $this->depth = $depth;
        foreach ($this->children as $child) {
            $child->updateDepths($depth + 1);
        }
    }

    /**
     * Calculates the maximum depth of the subtree rooted from this node.
     * @return int The maximum depth of the subtree rooted from this node.
     */
    public function maxDepth(): int
    {
        $depth = $this->depth;
        foreach ($this->children as $child) {
            if ($child->maxDepth() > $depth) {
                $depth = $child->maxDepth();
            }
        }
        return $depth;
    }

    /**
     * Recursive method that checks if all nodes in the subtree rooted with this node has the annotation in the given
     * layer.
     * @param ViewLayerType $layerType Layer name
     * @return bool True if all nodes in the subtree rooted with this node has the annotation in the given layer, false
     * otherwise.
     */
    public function layerExists(ViewLayerType $layerType): bool
    {
        if (count($this->children) == 0) {
            if ($this->getLayerData($layerType) != null) {
                return true;
            }
        } else {
            foreach ($this->children as $child) {
                if ($child->layerExists($layerType)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Checks if the current node is a dummy node or not. A node is a dummy node if its data contains '*', or its
     * data is '0' and its parent is '-NONE-'.
     * @return bool True if the current node is a dummy node, false otherwise.
     */
    public function isDummyNode(): bool
    {
        $data = $this->getLayerData(ViewLayerType::ENGLISH_WORD);
        $parentData = $this->parent->getLayerData(ViewLayerType::ENGLISH_WORD);
        $targetData = $this->getLayerData(ViewLayerType::TURKISH_WORD);
        if ($data != null && $parentData != null) {
            if ($targetData != null && str_contains($targetData, "*")) {
                return true;
            }
            return str_contains($data, "*") || ($data == "0" && $parentData == "-NONE-");
        } else {
            return false;
        }
    }

    /**
     * Checks if all nodes in the subtree rooted with this node has annotation with the given layer.
     * @param ViewLayerType $layerType Layer name
     * @return bool True if all nodes in the subtree rooted with this node has annotation with the given layer, false
     * otherwise.
     */
    public function layerAll(ViewLayerType $layerType): bool
    {
        if (count($this->children) == 0) {
            if ($this->getLayerData($layerType) == null && !$this->isDummyNode()) {
                return false;
            }
        } else {
            foreach ($this->children as $child) {
                if ($child->layerAll($layerType)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Recursive method to convert the subtree rooted with this node to a string. All parenthesis types are converted to
     * their regular forms.
     * @return string String version of the subtree rooted with this node.
     */
    public function toTurkishSentence(): string
    {
        if (count($this->children) == 0) {
            if ($this->getLayerData(ViewLayerType::TURKISH_WORD) != null
                && $this->getLayerData(ViewLayerType::TURKISH_WORD) != "*NONE*") {
                return " " . strtr($this->getLayerData(ViewLayerType::TURKISH_WORD),
                        ["-LRB" => "(", "-RRB-" => ")", "-LSB-" => "[", "-RSB-" => "]",
                            "-LCB-" => "{", "-RCB-" => "}", "-lrb-" => "(", "-rrb-" => ")",
                            "-lsb-" => "[", "-rsb-" => "]", "-lcb-" => "{", "-rcb-" => "}"]);
            } else {
                return " ";
            }
        } else {
            $st = "";
            foreach ($this->children as $child) {
                $st .= $child->toTurkishSentence();
            }
            return $st;
        }
    }

    /**
     * Sets the NER layer according to the tag of the parent node and the word in the node. The word is searched in the
     * gazetteer, if it exists, the NER info is replaced with the NER tag in the gazetter.
     * @param Gazetteer $gazetteer Gazetteer where we search the word
     * @param string $word Word to be searched in the gazetteer
     */
    public function checkGazetteer(Gazetteer $gazetteer, string $word): void{
        if ($gazetteer->contains($word) && $this->parent->getData()->getName() == "NNP") {
            $this->getLayerInfo()->setLayerData(ViewLayerType::NER, $gazetteer->getName());
        }
        if (str_contains($word, "'") &&
            $gazetteer->contains(mb_substr($word, 0, mb_strpos($word, "'") &&
            $this->parent->getData()->getName() == "NNP"))){
            $this->getLayerInfo()->setLayerData(ViewLayerType::NER, $gazetteer->getName());
        }
    }

    /**
     * Recursive method that sets the tag information of the given parse node with all descendants with respect to the
     * morphological annotation of the current node with all descendants.
     * @param ParseNode $parseNode Parse node whose tag information will be changed.
     * @param bool $surfaceForm If true, tag will be replaced with the surface form annotation.
     */
    public function generateParseNode(ParseNode $parseNode, bool $surfaceForm): void{
        if ($this->numberOfChildren() == 0){
            if ($surfaceForm){
                $parseNode->setData(new Symbol($this->getLayerData(ViewLayerType::TURKISH_WORD)));
            } else {
                $parseNode->setData(new Symbol($this->getLayerInfo()->getMorphologicaParseAt(0)->getWord()->getName()));
            }
        } else {
            $parseNode->setData($this->data);
            for ($i = 0; $i < $this->numberOfChildren(); $i++) {
                $newChild = new ParseNode();
                $parseNode->addChild($newChild);
                $this->children[$i]->generateParseNode($newChild, $surfaceForm);
            }
        }
    }

    /**
     * Recursive method to convert the subtree rooted with this node to a string.
     * @return String version of the subtree rooted with this node.
     */
    public function __toString(): string{
        if (count($this->children) < 2) {
            if (count($this->children) < 1){
                return $this->getLayerData();
            } else {
                return "(" . $this->data->getName() . " " . $this->children[0]->__toString() . ")";
            }
        } else {
            $st = "(" . $this->data->getName();
            foreach ($this->children as $child) {
                $st .= " " . $child->__toString();
            }
            return $st . ") ";
        }
    }
}