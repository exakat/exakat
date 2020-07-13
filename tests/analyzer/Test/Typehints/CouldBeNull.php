<?php

namespace Test\Typehints;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldBeNull extends Analyzer {
    /* 3 methods */

    public function testTypehints_CouldBeNull01()  { $this->generic_test('Typehints/CouldBeNull.01'); }
    public function testTypehints_CouldBeNull02()  { $this->generic_test('Typehints/CouldBeNull.02'); }
    public function testTypehints_CouldBeNull03()  { $this->generic_test('Typehints/CouldBeNull.03'); }
}
?>