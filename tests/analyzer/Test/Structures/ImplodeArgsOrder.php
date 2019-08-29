<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ImplodeArgsOrder extends Analyzer {
    /* 1 methods */

    public function testStructures_ImplodeArgsOrder01()  { $this->generic_test('Structures/ImplodeArgsOrder.01'); }
}
?>