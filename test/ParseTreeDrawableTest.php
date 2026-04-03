<?php

use olcaytaner\AnnotatedTree\ParseTreeDrawable;
use olcaytaner\AnnotatedTree\TreeBankDrawable;
use function PHPUnit\Framework\assertEquals;

class ParseTreeDrawableTest extends \PHPUnit\Framework\TestCase
{
    public function testParseTreeDrawable()
    {
        $tree0 = new ParseTreeDrawable("../trees/0000.dev");
        assertEquals(5, $tree0->maxDepth());
        assertEquals("(S (NP (NP (ADJP (ADJP yeni) (ADJP Büyük))  (NP yasada))  (NP (ADJP karmaşık) (NP dil)) )  (VP (NP savaşı) (VP bulandırmıştır))  (. .)) ", $tree0->generateParseTree(true)->toString());
        assertEquals("{turkish=yeni}{morphologicalAnalysis=yeni+ADJ}{metaMorphemes=yeni}{semantics=TUR10-0848740}{namedEntity=NONE}{propbank=ARG0\$TUR10-0122540} " .
            "{turkish=Büyük}{morphologicalAnalysis=büyük+ADJ}{metaMorphemes=büyük}{semantics=TUR10-0092410}{namedEntity=NONE}{propbank=ARG0\$TUR10-0122540} " .
            "{turkish=yasada}{morphologicalAnalysis=yasa+NOUN+A3SG+PNON+LOC}{metaMorphemes=yasa+DA}{semantics=TUR10-0411070}{namedEntity=NONE}{propbank=ARG0\$TUR10-0122540} " .
            "{turkish=karmaşık}{morphologicalAnalysis=karmaşık+ADJ}{metaMorphemes=karmaşık}{semantics=TUR10-0422250}{namedEntity=NONE}{propbank=ARG0\$TUR10-0122540} " .
            "{turkish=dil}{morphologicalAnalysis=dil+NOUN+A3SG+PNON+NOM}{metaMorphemes=dil}{semantics=TUR10-0204790}{namedEntity=NONE}{propbank=ARG0\$TUR10-0122540} " .
            "{turkish=savaşı}{morphologicalAnalysis=savaş+NOUN+A3SG+PNON+ACC}{metaMorphemes=savaş+yH}{semantics=TUR10-0135880}{namedEntity=NONE}{propbank=ARG1\$TUR10-0122540} " .
            "{turkish=bulandırmıştır}{morphologicalAnalysis=bulan+VERB^DB+VERB+CAUS+POS+NARR+COP+A3SG}{metaMorphemes=bulan+DHr+mHs+DHr}{semantics=TUR10-0122540}{namedEntity=NONE}{propbank=PREDICATE\$TUR10-0122540} " .
            "{turkish=.}{morphologicalAnalysis=.+PUNC}{metaMorphemes=.}{semantics=TUR10-1081860}{namedEntity=NONE}{propbank=NONE}", $tree0->generateAnnotatedSentence()->__toString());
    }

    public function testTreebank(): void{
        $treebank = new TreeBankDrawable("../trees");
    }
}