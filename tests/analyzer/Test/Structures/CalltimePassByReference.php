<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CalltimePassByReference extends Analyzer {
    /* 2 methods */

    public function testStructures_CalltimePassByReference01()  { $this->generic_test('Structures_CalltimePassByReference.01'); }
    public function testStructures_CalltimePassByReference02()  { $this->generic_test('Structures_CalltimePassByReference.02'); }
}
?>