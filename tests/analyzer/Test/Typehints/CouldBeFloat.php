<?php

namespace Test\Typehints;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldBeFloat extends Analyzer {
    /* 4 methods */

    public function testTypehints_CouldBeFloat01()  { $this->generic_test('Typehints/CouldBeFloat.01'); }
    public function testTypehints_CouldBeFloat02()  { $this->generic_test('Typehints/CouldBeFloat.02'); }
    public function testTypehints_CouldBeFloat03()  { $this->generic_test('Typehints/CouldBeFloat.03'); }
    public function testTypehints_CouldBeFloat04()  { $this->generic_test('Typehints/CouldBeFloat.04'); }
}
?>