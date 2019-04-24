<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NewLineStyle extends Analyzer {
    /* 3 methods */

    public function testStructures_NewLineStyle01()  { $this->generic_test('Structures/NewLineStyle.01'); }
    public function testStructures_NewLineStyle02()  { $this->generic_test('Structures/NewLineStyle.02'); }
    public function testStructures_NewLineStyle03()  { $this->generic_test('Structures/NewLineStyle.03'); }
}
?>