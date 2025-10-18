<?php

namespace olcaytaner\AnnotatedTree\Processor;

use olcaytaner\AnnotatedTree\ParseNodeDrawable;
use olcaytaner\AnnotatedTree\Processor\Condition\NodeDrawableCondition;

class NodeDrawableCollector
{
    private NodeDrawableCondition $condition;
    private ParseNodeDrawable $rootNode;

    /**
     * Constructor for the NodeDrawableCollector class. NodeDrawableCollector's main aim is to collect a set of
     * ParseNode's from a subtree rooted at rootNode, where the ParseNode's satisfy a given NodeCondition, which is
     * implemented by other interface class.
     * @param ParseNodeDrawable $rootNode Root node of the subtree
     * @param NodeDrawableCondition $condition The condition interface for which all nodes in the subtree rooted at rootNode will be checked
     */
    public function __construct(ParseNodeDrawable $rootNode, NodeDrawableCondition $condition){
        $this->condition = $condition;
        $this->rootNode = $rootNode;
    }

    /**
     * Private recursive method to check all descendants of the parseNode, if they ever satisfy the given node condition
     * @param ParseNodeDrawable $parseNode Root node of the subtree
     * @param array $collected The {@link ArrayList} where the collected ParseNode's will be stored.
     */
    private function collectNodes(ParseNodeDrawable $parseNode, array& $collected){
        if ($this->condition == null || $this->condition->satisfies($parseNode)) {
            $collected[] = $parseNode;
        }
        for ($i = 0; $i < $parseNode->numberOfChildren(); $i++) {
            $this->collectNodes($parseNode->getChild($i), $collected);
        }
    }

    /**
     * Collects and returns all ParseNodes satisfying the node condition.
     * @return array All ParseNodes satisfying the node condition.
     */
    public function collect():array{
        $result = [];
        $this->collectNodes($this->rootNode, $result);
        return $result;
    }
}