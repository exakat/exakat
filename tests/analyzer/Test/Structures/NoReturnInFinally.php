<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoReturnInFinally extends Analyzer {
    /* 1 methods */

    public function testStructures_NoReturnInFinally01()  { $this->generic_test('Structures/NoReturnInFinally.01'); }
}
?>