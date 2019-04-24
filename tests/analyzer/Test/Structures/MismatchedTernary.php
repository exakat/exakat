<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MismatchedTernary extends Analyzer {
    /* 2 methods */

    public function testStructures_MismatchedTernary01()  { $this->generic_test('Structures/MismatchedTernary.01'); }
    public function testStructures_MismatchedTernary02()  { $this->generic_test('Structures/MismatchedTernary.02'); }
}
?>