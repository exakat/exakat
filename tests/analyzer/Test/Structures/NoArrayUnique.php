<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoArrayUnique extends Analyzer {
    /* 1 methods */

    public function testStructures_NoArrayUnique01()  { $this->generic_test('Structures_NoArrayUnique.01'); }
}
?>