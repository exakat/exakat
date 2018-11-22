<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class toStringThrowsException extends Analyzer {
    /* 2 methods */

    public function testStructures_toStringThrowsException01()  { $this->generic_test('Structures_toStringThrowsException.01'); }
    public function testStructures_toStringThrowsException02()  { $this->generic_test('Structures/toStringThrowsException.02'); }
}
?>