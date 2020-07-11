<?php

namespace Test\Typehints;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldBeCIT extends Analyzer {
    /* 3 methods */

    public function testTypehints_CouldBeCIT01()  { $this->generic_test('Typehints/CouldBeCIT.01'); }
    public function testTypehints_CouldBeCIT02()  { $this->generic_test('Typehints/CouldBeCIT.02'); }
    public function testTypehints_CouldBeCIT03()  { $this->generic_test('Typehints/CouldBeCIT.03'); }
}
?>