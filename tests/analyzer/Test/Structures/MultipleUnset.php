<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MultipleUnset extends Analyzer {
    /* 2 methods */

    public function testStructures_MultipleUnset01()  { $this->generic_test('Structures/MultipleUnset.01'); }
    public function testStructures_MultipleUnset02()  { $this->generic_test('Structures/MultipleUnset.02'); }
}
?>