<?php

namespace olcaytaner\AnnotatedTree\Processor;

use olcaytaner\AnnotatedTree\ParseNodeDrawable;
use olcaytaner\AnnotatedTree\ParseTreeDrawable;
use olcaytaner\AnnotatedTree\Processor\NodeModification\NodeModifier;

class TreeModifier
{
    private ParseTreeDrawable $parseTree;
    private NodeModifier $nodeModifier;

    private function nodeModifyPrivate(ParseNodeDrawable $parseNode): void{
        $this->nodeModifier->modifier($parseNode);
        for ($i = 0; $i < $parseNode->numberOfChildren(); $i++) {
            $this->nodeModify($parseNode->getChild($i));
        }
    }

    public function nodeModify(): void{
        $this->nodeModifyPrivate($this->parseTree->getRoot());
    }
}