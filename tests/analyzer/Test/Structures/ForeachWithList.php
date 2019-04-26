<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ForeachWithList extends Analyzer {
    /* 1 methods */

    public function testStructures_ForeachWithList01()  { $this->generic_test('Structures_ForeachWithList.01'); }
}
?>