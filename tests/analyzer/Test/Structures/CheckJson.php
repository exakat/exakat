<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CheckJson extends Analyzer {
    /* 2 methods */

    public function testStructures_CheckJson01()  { $this->generic_test('Structures/CheckJson.01'); }
    public function testStructures_CheckJson02()  { $this->generic_test('Structures/CheckJson.02'); }
}
?>