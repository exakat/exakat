<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MultipleDeclareStrict extends Analyzer {
    /* 3 methods */

    public function testPhp_MultipleDeclareStrict01()  { $this->generic_test('Php/MultipleDeclareStrict.01'); }
    public function testPhp_MultipleDeclareStrict02()  { $this->generic_test('Php/MultipleDeclareStrict.02'); }
    public function testPhp_MultipleDeclareStrict03()  { $this->generic_test('Php/MultipleDeclareStrict.03'); }
}
?>