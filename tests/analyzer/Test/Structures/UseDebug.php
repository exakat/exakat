<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UseDebug extends Analyzer {
    /* 1 methods */

    public function testStructures_UseDebug01()  { $this->generic_test('Structures/UseDebug.01'); }
}
?>