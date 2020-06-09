<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SGVariablesConfusion extends Analyzer {
    /* 1 methods */

    public function testStructures_SGVariablesConfusion01()  { $this->generic_test('Structures/SGVariablesConfusion.01'); }
}
?>