<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class MissingCases extends Analyzer {
    /* 1 methods */

    public function testStructures_MissingCases01()  { $this->generic_test('Structures/MissingCases.01'); }
}
?>