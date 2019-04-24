<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseInstanceof extends Analyzer {
    /* 1 methods */

    public function testStructures_UseInstanceof01()  { $this->generic_test('Structures/UseInstanceof.01'); }
}
?>