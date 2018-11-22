<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class MissingNew extends Analyzer {
    /* 3 methods */

    public function testStructures_MissingNew01()  { $this->generic_test('Structures/MissingNew.01'); }
    public function testStructures_MissingNew02()  { $this->generic_test('Structures/MissingNew.02'); }
    public function testStructures_MissingNew03()  { $this->generic_test('Structures/MissingNew.03'); }
}
?>