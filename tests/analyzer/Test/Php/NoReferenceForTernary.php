<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoReferenceForTernary extends Analyzer {
    /* 4 methods */

    public function testPhp_NoReferenceForTernary01()  { $this->generic_test('Php/NoReferenceForTernary.01'); }
    public function testPhp_NoReferenceForTernary02()  { $this->generic_test('Php/NoReferenceForTernary.02'); }
    public function testPhp_NoReferenceForTernary03()  { $this->generic_test('Php/NoReferenceForTernary.03'); }
    public function testPhp_NoReferenceForTernary04()  { $this->generic_test('Php/NoReferenceForTernary.04'); }
}
?>