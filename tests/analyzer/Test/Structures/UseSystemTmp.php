<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseSystemTmp extends Analyzer {
    /* 1 methods */

    public function testStructures_UseSystemTmp01()  { $this->generic_test('Structures/UseSystemTmp.01'); }
}
?>