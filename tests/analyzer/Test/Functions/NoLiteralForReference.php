<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoLiteralForReference extends Analyzer {
    /* 5 methods */

    public function testFunctions_NoLiteralForReference01()  { $this->generic_test('Functions/NoLiteralForReference.01'); }
    public function testFunctions_NoLiteralForReference02()  { $this->generic_test('Functions/NoLiteralForReference.02'); }
    public function testFunctions_NoLiteralForReference03()  { $this->generic_test('Functions/NoLiteralForReference.03'); }
    public function testFunctions_NoLiteralForReference04()  { $this->generic_test('Functions/NoLiteralForReference.04'); }
    public function testFunctions_NoLiteralForReference05()  { $this->generic_test('Functions/NoLiteralForReference.05'); }
}
?>