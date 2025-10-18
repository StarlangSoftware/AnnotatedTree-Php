<?php

namespace olcaytaner\AnnotatedTree\Processor\Condition;

use olcaytaner\AnnotatedTree\ParseNodeDrawable;

interface NodeDrawableCondition
{
    public function satisfies(ParseNodeDrawable $parseNode): bool;
}