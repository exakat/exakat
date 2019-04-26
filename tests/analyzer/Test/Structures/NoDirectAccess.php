<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoDirectAccess extends Analyzer {
    /* 4 methods */

    public function testStructures_NoDirectAccess01()  { $this->generic_test('Structures_NoDirectAccess.01'); }
    public function testStructures_NoDirectAccess02()  { $this->generic_test('Structures_NoDirectAccess.02'); }
    public function testStructures_NoDirectAccess03()  { $this->generic_test('Structures_NoDirectAccess.03'); }
    public function testStructures_NoDirectAccess04()  { $this->generic_test('Structures/NoDirectAccess.04'); }
}
?>