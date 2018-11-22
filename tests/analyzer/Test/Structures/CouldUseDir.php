<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CouldUseDir extends Analyzer {
    /* 1 methods */

    public function testStructures_CouldUseDir01()  { $this->generic_test('Structures/CouldUseDir.01'); }
}
?>