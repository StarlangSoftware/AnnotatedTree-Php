<?php

namespace olcaytaner\AnnotatedTree;

use olcaytaner\AnnotatedSentence\AnnotatedSentence;
use olcaytaner\AnnotatedSentence\AnnotatedWord;
use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\AnnotatedTree\Processor\Condition\IsEnglishLeafNode;
use olcaytaner\AnnotatedTree\Processor\Condition\IsTurkishLeafNode;
use olcaytaner\AnnotatedTree\Processor\NodeDrawableCollector;
use olcaytaner\Corpus\FileDescription;
use olcaytaner\ParseTree\ParseNode;
use olcaytaner\ParseTree\ParseTree;
use olcaytaner\ParseTree\Symbol;

class ParseTreeDrawable extends ParseTree
{
    private FileDescription $fileDescription;

    /**
     * Another constructor for the ParseTreeDrawable. Sets the file description and reads the tree from the file
     * description.
     * @param FileDescription $fileDescription File description that contains the path, index and extension information.
     */
    public function constructor1(FileDescription $fileDescription): void
    {
        $this->fileDescription = $fileDescription;
        $this->readFromFile($fileDescription->getPath());
    }

    /**
     * Another constructor for the ParseTreeDrawable. Sets the file description and reads the tree from the file
     * description.
     * @param string $path Path of the tree
     */
    public function constructor2(string $path): void
    {
        $this->readFromFile($path);
    }

    /**
     * Another constructor for the ParseTreeDrawable. Sets the file description and reads the tree from the file
     * description.
     * @param string $path Path of the tree
     * @param FileDescription $fileDescription File description that contains the path, index and extension information.
     */
    public function constructor3(string $path, FileDescription $fileDescription): void
    {
        $this->fileDescription = new FileDescription($path, $fileDescription->getExtension(), $fileDescription->getIndex());
        $this->readFromFile($this->fileDescription->getPath());
    }

    /**
     * Constructor for the ParseTreeDrawable. Sets the file description and reads the tree from the file description.
     * @param string $path Path of the tree
     * @param string $rawFileName File name of the tree such as 0123.train.
     */
    public function constructor4(string $path, string $rawFileName): void
    {
        $this->fileDescription = new FileDescription($path, $rawFileName);
        $this->readFromFile($this->fileDescription->getPath());
    }

    /**
     * Another constructor for the ParseTreeDrawable. Sets the file description and reads the tree from the file
     * description.
     * @param string $path Path of the tree
     * @param string $extension Extension of the file such as train, test or dev.
     * @param int $index Index of the file such as 1235.
     */
    public function constructor5(string $path, string $extension, int $index): void
    {
        $this->fileDescription = new FileDescription($path, $extension, $index);
        $this->readFromFile($this->fileDescription->getPath());
    }

    public function __construct(FileDescription|string|null $fileDescriptionOrPath = null, FileDescription|string|null $fileDescriptionOrFileName = null, int|null $index = null){
        parent::__construct();
        if ($fileDescriptionOrPath instanceof FileDescription){
            $this->constructor1($fileDescriptionOrPath);
        } elseif ($fileDescriptionOrFileName == null){
            $this->constructor2($fileDescriptionOrPath);
        } elseif ($fileDescriptionOrFileName instanceof FileDescription){
            $this->constructor3($fileDescriptionOrPath, $fileDescriptionOrFileName);
        } elseif ($index == null){
            $this->constructor4($fileDescriptionOrPath, $fileDescriptionOrFileName);
        } else {
            $this->constructor5($fileDescriptionOrPath, $fileDescriptionOrFileName, $index);
        }
    }

    /**
     * Mutator method for the fileDescription attribute.
     * @param FileDescription $fileDescription New fileDescription value.
     */
    public function setFileDescription(FileDescription $fileDescription): void
    {
        $this->fileDescription = $fileDescription;
    }

    /**
     * Accessor method for the fileDescription attribute.
     * @return FileDescription FileDescription attribute.
     */
    public function getFileDescription(): FileDescription
    {
        return $this->fileDescription;
    }

    /**
     * Reloads the tree from the input file.
     */
    public function reload(): void{
        $this->readFromFile($this->fileDescription->getPath());
    }

    /**
     * Mutator for the root attribute.
     * @param ParseNode $newRootNode New root node.
     */
    public function setRoot(ParseNode $newRootNode): void{
        $this->root = $newRootNode;
    }

    /**
     * Reads the parse tree from the given file description with path replaced with the currentPath. It sets the root
     * node which calls ParseNodeDrawable constructor recursively.
     * @param string $currentPath Path of the tree
     */
    public function readFromFile(string $currentPath): void{
        $data = file_get_contents($currentPath);
        $line = explode("\n", $data)[0];
        if (str_contains($line, "(") && str_contains($line, ")")) {
            $line = trim(mb_substr($line, mb_strpos($line, "(") + 1, mb_strrpos($line, ")") - mb_strpos($line, "(") - 1));
            $this->root = new ParseNodeDrawable(null, $line, false, 0);
            $this->updateTraversalIndexes();
        }
    }

    /**
     * Loads the next tree according to the index of the parse tree. For example, if the current
     * tree fileName is 0123.train, after the call of nextTree(3), the method will load 0126.train. If the next tree
     * does not exist, nothing will happen.
     * @param int $count Number of trees to go forward
     */
    public function nextTree(int $count): void{
        if ($this->fileDescription->nextFileExists($count)){
            $this->fileDescription->addToIndex($count);
            $this->reload();
        }
    }

    /**
     * Loads the previous tree according to the index of the parse tree. For example, if the current
     * tree fileName is 0123.train, after the call of previousTree(4), the method will load 0119.train. If the
     * previous tree does not exist, nothing will happen.
     * @param int $count Number of trees to go backward
     */
    public function previousTree(int $count): void{
        if ($this->fileDescription->previousFileExists($count)){
            $this->fileDescription->addToIndex(-$count);
            $this->reload();
        }
    }

    /**
     * Swaps the given child node of this node with the previous sibling of that given node. If the given node is the
     * leftmost child, it swaps with the last node.
     * @param ParseNode $node Node to be swapped.
     */
    public function moveLeft(ParseNode $node): void{
        if ($this->root != $node){
            $this->root->moveLeft($node);
            $this->updateTraversalIndexes();
        }
    }

    /**
     * Swaps the given child node of this node with the next sibling of that given node. If the given node is the
     * rightmost child, it swaps with the first node.
     * @param ParseNode $node Node to be swapped.
     */
    public function moveRight(ParseNode $node): void{
        if ($this->root != $node){
            $this->root->moveRight($node);
            $this->updateTraversalIndexes();
        }
    }

    /**
     * Divides the given node into multiple parse nodes if it contains more than one word. The parent node will be
     * the same for the new nodes, original node is deleted from the children, the pos tags of the new parse nodes will
     * be determined according to their morphological parses.
     * @param ParseNodeDrawable $parseNode Parse node to be divided
     */
    public function divideIntoWords(ParseNodeDrawable $parseNode): void{
        $layers = $parseNode->getLayerInfo()->divideIntoWords();
        $parseNode->getParent()->removeChild($parseNode);
        foreach ($layers as $layerInfo){
            if ($layerInfo->layerExists(ViewLayerType::INFLECTIONAL_GROUP)){
                $symbol = new Symbol($layerInfo->getMorphologicalParseAt(0)->getTreePos());
            } else {
                $symbol = new Symbol("-XXX-");
            }
            $child = new ParseNodeDrawable($symbol);
            $parseNode->getParent()->addChild($child);
            $grandChild = new ParseNodeDrawable($child, $layerInfo->getLayerDescription(), true, $parseNode->getDepth() + 1);
            $child->addChild($grandChild);
            $this->root->updateDepths(0);
        }
    }

    /**
     * Moves the subtree rooted at fromNode as a child to the node toNode at position childIndex.
     * @param ParseNode $fromNode Subtree root node to be moved.
     * @param ParseNode $toNode Node to which a new subtree will be added.
     * @param int|null $childIndex New child index of the toNode.
     */
    public function moveNode(ParseNode $fromNode, ParseNode $toNode, int|null $childIndex): void{
        if ($this->root != $fromNode){
            $parent = $fromNode->getParent();
            $parent->removeChild($fromNode);
            $toNode->addChild($fromNode, $childIndex);
            $this->updateTraversalIndexes();
            $this->root->updateDepths(0);
        }
    }

    /**
     * Removed the first child of the parent node and adds the given child node as a child to that node.
     * @param ParseNodeDrawable $parent Parent node.
     * @param ParseNodeDrawable $child New child node to be added.
     */
    public function combineWords(ParseNodeDrawable $parent, ParseNodeDrawable $child): void{
        while ($parent->numberOfChildren() > 0){
            $parent->removeChild($parent->firstChild());
        }
        $parent->addChild($child);
    }

    /**
     * The method checks if all nodes in the tree has the annotation in the given layer.
     * @param ViewLayerType $layerType Layer name
     * @return bool True if all nodes in the tree has the annotation in the given layer, false otherwise.
     */
    public function layerExists(ViewLayerType $layerType): bool{
        return $this->root->layerExists($layerType);
    }

    /**
     * Checks if all nodes in the tree has annotation with the given layer.
     * @param ViewLayerType $layerType Layer name
     * @return bool True if all nodes in the tree has annotation with the given layer, false otherwise.
     */
    public function layerAll(ViewLayerType $layerType): bool{
        return $this->root->layerAll($layerType);
    }

    /**
     * Clears the given layer for all nodes in the tree
     * @param ViewLayerType $layerType Layer name
     */
    public function clearLayer(ViewLayerType $layerType): void{
        if ($this->root != null){
            $this->root->clearLayer($layerType);
        }
    }

    /**
     * Returns the leaf node that comes one after the given parse node according to the inorder traversal.
     * @param ParseNodeDrawable|ParseNode $parseNode Input parse node.
     * @return ParseNodeDrawable|null The leaf node that comes one after the given parse node according to the inorder traversal.
     */
    public function nextLeafNode(ParseNodeDrawable|ParseNode $parseNode): ?ParseNodeDrawable{
        $nodeDrawableCollector = new NodeDrawableCollector($this->root, new IsTurkishLeafNode());
        $leafList = $nodeDrawableCollector->collect();
        for ($i = 0; $i < count($leafList) - 1; $i++){
            if ($leafList[$i] == $parseNode){
                return $leafList[$i + 1];
            }
        }
        return null;
    }

    /**
     * Returns the leaf node that comes one before the given parse node according to the inorder traversal.
     * @param ParseNodeDrawable|ParseNode $parseNode Input parse node.
     * @return ParseNodeDrawable|null The leaf node that comes one before the given parse node according to the inorder traversal.
     */
    public function previousLeafNode(ParseNodeDrawable|ParseNode $parseNode): ?ParseNodeDrawable{
        $nodeDrawableCollector = new NodeDrawableCollector($this->root, new IsTurkishLeafNode());
        $leafList = $nodeDrawableCollector->collect();
        for ($i = 1; $i < count($leafList); $i++){
            if ($leafList[$i] == $parseNode){
                return $leafList[$i - 1];
            }
        }
        return null;
    }

    /**
     * Constructs an AnnotatedSentence object from the Turkish tree. Collects all leaf nodes, then for each leaf node
     * converts layer info of all words at that node to AnnotatedWords. Layers are converted to the counterparts in the
     * AnnotatedWord.
     * @param string|null $language Language for which annotated sentence is created.
     * @return AnnotatedSentence AnnotatedSentence counterpart of the Turkish tree
     */
    public function generateAnnotatedSentence(?string $language = null): AnnotatedSentence{
        if ($language == null){
            $sentence = new AnnotatedSentence();
            $nodeDrawableCollector = new NodeDrawableCollector($this->root, new IsTurkishLeafNode());
            $leafList = $nodeDrawableCollector->collect();
            foreach ($leafList as $leaf){
                if ($leaf instanceof ParseNodeDrawable){
                    $layers = $leaf->getLayerInfo();
                    for ($i = 0; $i < $layers->getNumberOfWords(); $i++){
                        $sentence->addWord($layers->toAnnotatedWord($i));
                    }
                }
            }
            return $sentence;
        } else {
            $sentence = new AnnotatedSentence();
            $nodeDrawableCollector = new NodeDrawableCollector($this->root, new IsEnglishLeafNode());
            $leafList = $nodeDrawableCollector->collect();
            foreach ($leafList as $leaf){
                if ($leaf instanceof ParseNodeDrawable){
                    $newWord = new AnnotatedWord("{" . $language . "=" . $leaf->getData()->getName() . "}{posTag="
                        . $leaf->getParent()->getData()->getName() . "}");
                    $sentence->addWord($newWord);
                }
            }
            return $sentence;
        }
    }

    /**
     * Recursive method that generates a new parse tree by replacing the tag information of the all parse nodes (with all
     * its descendants) with respect to the morphological annotation of all parse nodes (with all its descendants)
     * of the current parse tree.
     * @param bool $surfaceForm If true, tag will be replaced with the surface form annotation.
     * @return ParseTree A new parse tree by replacing the tag information of the all parse nodes with respect to the
     * morphological annotation of all parse nodes of the current parse tree.
     */
    public function generateParseTree(bool $surfaceForm): ParseTree{
        $rootNode = $this->root;
        $result = new ParseTree(new ParseNode($rootNode->getData()));
        $rootNode->generateParseNode($result->getRoot(), $surfaceForm);
        return $result;
    }

}