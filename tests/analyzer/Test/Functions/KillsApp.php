<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class KillsApp extends Analyzer {
    /* 2 methods */

    public function testFunctions_KillsApp01()  { $this->generic_test('Functions_KillsApp.01'); }
    public function testFunctions_KillsApp02()  { $this->generic_test('Functions_KillsApp.02'); }
}
?>