<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseCountRecursive extends Analyzer {
    /* 3 methods */

    public function testStructures_UseCountRecursive01()  { $this->generic_test('Structures/UseCountRecursive.01'); }
    public function testStructures_UseCountRecursive02()  { $this->generic_test('Structures/UseCountRecursive.02'); }
    public function testStructures_UseCountRecursive03()  { $this->generic_test('Structures/UseCountRecursive.03'); }
}
?>