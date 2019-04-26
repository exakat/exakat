<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class FunctionPreSubscripting extends Analyzer {
    /* 4 methods */

    public function testStructures_FunctionPreSubscripting01()  { $this->generic_test('Structures_FunctionPreSubscripting.01'); }
    public function testStructures_FunctionPreSubscripting02()  { $this->generic_test('Structures/FunctionPreSubscripting.02'); }
    public function testStructures_FunctionPreSubscripting03()  { $this->generic_test('Structures/FunctionPreSubscripting.03'); }
    public function testStructures_FunctionPreSubscripting04()  { $this->generic_test('Structures/FunctionPreSubscripting.04'); }
}
?>