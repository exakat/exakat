<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseUrlQueryFunctions extends Analyzer {
    /* 1 methods */

    public function testStructures_UseUrlQueryFunctions01()  { $this->generic_test('Structures/UseUrlQueryFunctions.01'); }
}
?>