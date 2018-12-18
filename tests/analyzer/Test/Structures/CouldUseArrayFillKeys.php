<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CouldUseArrayFillKeys extends Analyzer {
    /* 5 methods */

    public function testStructures_CouldUseArrayFillKeys01()  { $this->generic_test('Structures/CouldUseArrayFillKeys.01'); }
    public function testStructures_CouldUseArrayFillKeys02()  { $this->generic_test('Structures/CouldUseArrayFillKeys.02'); }
    public function testStructures_CouldUseArrayFillKeys03()  { $this->generic_test('Structures/CouldUseArrayFillKeys.03'); }
    public function testStructures_CouldUseArrayFillKeys04()  { $this->generic_test('Structures/CouldUseArrayFillKeys.04'); }
    public function testStructures_CouldUseArrayFillKeys05()  { $this->generic_test('Structures/CouldUseArrayFillKeys.05'); }
}
?>