<?php

namespace olcaytaner\AnnotatedTree\Processor;

use olcaytaner\AnnotatedTree\ParseNodeDrawable;
use olcaytaner\AnnotatedTree\ParseTreeDrawable;
use olcaytaner\AnnotatedTree\Processor\LeafConverter\LeafToStringConverter;

class TreeToStringConverter
{
    private LeafToStringConverter $converter;
    private ParseTreeDrawable $parseTree;

    /**
     * Constructor of the TreeToStringConverter class. Sets the attributes.
     * @param ParseTreeDrawable $parseTree Parse tree to be converted.
     * @param LeafToStringConverter $converter Node to string converter interface.
     */
    public function __construct(ParseTreeDrawable $parseTree, LeafToStringConverter $converter){
        $this->converter = $converter;
        $this->parseTree = $parseTree;
    }

    /**
     * Converts recursively a parse node to a string. If it is a leaf node, calls the converter's leafConverter method,
     * otherwise concatenates the converted strings of its children.
     * @param ParseNodeDrawable $parseNode Parse node to convert to string.
     * @return string String form of the parse node and all of its descendants.
     */
    private function convertToString(ParseNodeDrawable $parseNode): string{
        if ($parseNode->isLeaf()){
            return $this->converter->leafConverter($parseNode);
        } else {
            $st = "";
            for ($i = 0; $i < $parseNode->numberOfChildren(); $i++){
                $st .= $this->convertToString($parseNode->getChild($i));
            }
            return $st;
        }
    }

    /**
     * Calls the convertToString method with root of the tree to convert the parse tree to string.
     * @return string String form of the parse tree.
     */
    public function convert(): string{
        return $this->convertToString($this->parseTree->getRoot());
    }
}