<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoGetClassNull extends Analyzer {
    /* 1 methods */

    public function testStructures_NoGetClassNull01()  { $this->generic_test('Structures/NoGetClassNull.01'); }
}
?>