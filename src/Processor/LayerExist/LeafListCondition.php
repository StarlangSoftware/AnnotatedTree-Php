<?php

namespace olcaytaner\AnnotatedTree\Processor\LayerExist;

interface LeafListCondition
{
    function satisfies(array $leafList): bool;
}