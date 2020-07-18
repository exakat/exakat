<?php

namespace Test\Typehints;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldBeParent extends Analyzer {
    /* 2 methods */

    public function testTypehints_CouldBeParent01()  { $this->generic_test('Typehints/CouldBeParent.01'); }
    public function testTypehints_CouldBeParent02()  { $this->generic_test('Typehints/CouldBeParent.02'); }
}
?>