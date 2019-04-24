<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CryptWithoutSalt extends Analyzer {
    /* 1 methods */

    public function testStructures_CryptWithoutSalt01()  { $this->generic_test('Structures_CryptWithoutSalt.01'); }
}
?>