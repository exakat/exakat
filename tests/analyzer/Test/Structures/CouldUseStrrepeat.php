<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldUseStrrepeat extends Analyzer {
    /* 2 methods */

    public function testStructures_CouldUseStrrepeat01()  { $this->generic_test('Structures/CouldUseStrrepeat.01'); }
    public function testStructures_CouldUseStrrepeat02()  { $this->generic_test('Structures/CouldUseStrrepeat.02'); }
}
?>