<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoReferenceForTernary extends Analyzer {
    /* 2 methods */

    public function testPhp_NoReferenceForTernary01()  { $this->generic_test('Php/NoReferenceForTernary.01'); }
    public function testPhp_NoReferenceForTernary02()  { $this->generic_test('Php/NoReferenceForTernary.02'); }
}
?>