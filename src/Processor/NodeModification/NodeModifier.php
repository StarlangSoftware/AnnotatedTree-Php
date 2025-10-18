<?php

namespace olcaytaner\AnnotatedTree\Processor\NodeModification;

use olcaytaner\AnnotatedTree\ParseNodeDrawable;

interface NodeModifier
{
    public function modifier(ParseNodeDrawable $parseNode): void;
}