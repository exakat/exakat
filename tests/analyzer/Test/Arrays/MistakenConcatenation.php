<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class MistakenConcatenation extends Analyzer {
    /* 2 methods */

    public function testArrays_MistakenConcatenation01()  { $this->generic_test('Arrays/MistakenConcatenation.01'); }
    public function testArrays_MistakenConcatenation02()  { $this->generic_test('Arrays/MistakenConcatenation.02'); }
}
?>