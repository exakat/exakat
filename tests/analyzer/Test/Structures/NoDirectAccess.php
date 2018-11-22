<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoDirectAccess extends Analyzer {
    /* 3 methods */

    public function testStructures_NoDirectAccess01()  { $this->generic_test('Structures_NoDirectAccess.01'); }
    public function testStructures_NoDirectAccess02()  { $this->generic_test('Structures_NoDirectAccess.02'); }
    public function testStructures_NoDirectAccess03()  { $this->generic_test('Structures_NoDirectAccess.03'); }
}
?>