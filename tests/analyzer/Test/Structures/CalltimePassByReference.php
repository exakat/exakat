<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CalltimePassByReference extends Analyzer {
    /* 2 methods */

    public function testStructures_CalltimePassByReference01()  { $this->generic_test('Structures_CalltimePassByReference.01'); }
    public function testStructures_CalltimePassByReference02()  { $this->generic_test('Structures_CalltimePassByReference.02'); }
}
?>