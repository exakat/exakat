<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetAside extends Analyzer {
    /* 2 methods */

    public function testStructures_SetAside01()  { $this->generic_test('Structures/SetAside.01'); }
    public function testStructures_SetAside02()  { $this->generic_test('Structures/SetAside.02'); }
}
?>