<?php

namespace olcaytaner\AnnotatedTree\Processor\LeafConverter;

use olcaytaner\AnnotatedTree\ParseNodeDrawable;

interface LeafToStringConverter
{
    public function leafConverter(ParseNodeDrawable $leafNode): string;
}