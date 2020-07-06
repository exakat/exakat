<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ExtendedTypehints extends Analyzer {
    /* 8 methods */

    public function testComplete_ExtendedTypehints01()  { $this->generic_test('Complete/ExtendedTypehints.01'); }
    public function testComplete_ExtendedTypehints02()  { $this->generic_test('Complete/ExtendedTypehints.02'); }
    public function testComplete_ExtendedTypehints03()  { $this->generic_test('Complete/ExtendedTypehints.03'); }
    public function testComplete_ExtendedTypehints04()  { $this->generic_test('Complete/ExtendedTypehints.04'); }
    public function testComplete_ExtendedTypehints05()  { $this->generic_test('Complete/ExtendedTypehints.05'); }
    public function testComplete_ExtendedTypehints06()  { $this->generic_test('Complete/ExtendedTypehints.06'); }
    public function testComplete_ExtendedTypehints07()  { $this->generic_test('Complete/ExtendedTypehints.07'); }
    public function testComplete_ExtendedTypehints08()  { $this->generic_test('Complete/ExtendedTypehints.08'); }
}
?>