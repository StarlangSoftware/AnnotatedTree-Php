<?php

namespace olcaytaner\AnnotatedTree;

use olcaytaner\AnnotatedSentence\ViewLayerType;
use olcaytaner\ParseTree\TreeBank;

class TreeBankDrawable extends TreeBank
{
    /**
     * A constructor of {@link TreeBankDrawable} class which reads all {@link ParseTreeDrawable} files with the file
     * name satisfying the given pattern inside the given folder. For each file inside that folder, the constructor
     * creates a ParseTreeDrawable and puts in inside the list parseTrees.
     * @param string|null $folder Folder where all parseTrees reside.
     * @param string|null $pattern File pattern such as "." ".train" ".test".
     */
    public function __construct(?string $folder = null, ?string $pattern = null)
    {
        parent::__construct();
        if ($pattern == null) {
            foreach (glob($folder . '/*.*') as $file) {
                $parseTree = new ParseTreeDrawable($file);
                $parseTree->setName($file);
                $this->parseTrees[] = $parseTree;
            }
        } else {
            foreach (glob($folder . '/' . $pattern) as $file) {
                $parseTree = new ParseTreeDrawable($file);
                $parseTree->setName($file);
                $this->parseTrees[] = $parseTree;
            }
        }
    }

    /**
     * Accessor for the parseTrees attribute
     * @return array ParseTrees attribute
     */
    public function getParseTrees(): array{
        return $this->parseTrees;
    }

    /**
     * Accessor for a specific tree with the given position in the array.
     * @param int $index Index of the parseTree.
     * @return ParseTreeDrawable Tree that is in the position index
     */
    public function get(int $index): ParseTreeDrawable{
        return $this->parseTrees[$index];
    }

    /**
     * Clears the given layer for all nodes in all trees
     * @param ViewLayerType $layerType Layer name
     */
    public function clearLayer(ViewLayerType $layerType): void{
        foreach ($this->parseTrees as $parseTree){
            $parseTree->clearLayer($layerType);
        }
    }

    public function removeTree(int $index): void{
        array_splice($this->parseTrees, $index, 1);
    }

}