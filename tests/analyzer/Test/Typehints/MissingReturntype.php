<?php

namespace Test\Typehints;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MissingReturntype extends Analyzer {
    /* 4 methods */

    public function testTypehints_MissingReturntype01()  { $this->generic_test('Typehints/MissingReturntype.01'); }
    public function testTypehints_MissingReturntype02()  { $this->generic_test('Typehints/MissingReturntype.02'); }
    public function testTypehints_MissingReturntype03()  { $this->generic_test('Typehints/MissingReturntype.03'); }
    public function testTypehints_MissingReturntype04()  { $this->generic_test('Typehints/MissingReturntype.04'); }
}
?>