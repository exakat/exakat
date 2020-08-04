<?php

namespace Test\Typehints;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldBeString extends Analyzer {
    /* 6 methods */

    public function testTypehints_CouldBeString01()  { $this->generic_test('Typehints/CouldBeString.01'); }
    public function testTypehints_CouldBeString02()  { $this->generic_test('Typehints/CouldBeString.02'); }
    public function testTypehints_CouldBeString03()  { $this->generic_test('Typehints/CouldBeString.03'); }
    public function testTypehints_CouldBeString04()  { $this->generic_test('Typehints/CouldBeString.04'); }
    public function testTypehints_CouldBeString05()  { $this->generic_test('Typehints/CouldBeString.05'); }
    public function testTypehints_CouldBeString06()  { $this->generic_test('Typehints/CouldBeString.06'); }
}
?>