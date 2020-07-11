<?php

namespace Test\Typehints;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldBeInt extends Analyzer {
    /* 3 methods */

    public function testTypehints_CouldBeInt01()  { $this->generic_test('Typehints/CouldBeInt.01'); }
    public function testTypehints_CouldBeInt02()  { $this->generic_test('Typehints/CouldBeInt.02'); }
    public function testTypehints_CouldBeInt03()  { $this->generic_test('Typehints/CouldBeInt.03'); }
}
?>