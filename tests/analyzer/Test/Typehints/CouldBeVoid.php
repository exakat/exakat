<?php

namespace Test\Typehints;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldBeVoid extends Analyzer {
    /* 2 methods */

    public function testTypehints_CouldBeVoid01()  { $this->generic_test('Typehints/CouldBeVoid.01'); }
    public function testTypehints_CouldBeVoid02()  { $this->generic_test('Typehints/CouldBeVoid.02'); }
}
?>