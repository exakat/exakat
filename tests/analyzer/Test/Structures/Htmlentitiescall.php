<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Htmlentitiescall extends Analyzer {
    /* 4 methods */

    public function testStructures_Htmlentitiescall01()  { $this->generic_test('Structures_Htmlentitiescall.01'); }
    public function testStructures_Htmlentitiescall02()  { $this->generic_test('Structures_Htmlentitiescall.02'); }
    public function testStructures_Htmlentitiescall03()  { $this->generic_test('Structures_Htmlentitiescall.03'); }
    public function testStructures_Htmlentitiescall04()  { $this->generic_test('Structures/Htmlentitiescall.04'); }
}
?>