<?php

namespace Test\Typehints;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldBeIterable extends Analyzer {
    /* 3 methods */

    public function testTypehints_CouldBeIterable01()  { $this->generic_test('Typehints/CouldBeIterable.01'); }
    public function testTypehints_CouldBeIterable02()  { $this->generic_test('Typehints/CouldBeIterable.02'); }
    public function testTypehints_CouldBeIterable03()  { $this->generic_test('Typehints/CouldBeIterable.03'); }
}
?>