<?php

namespace Test\Variables;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Blind extends Analyzer {
    /* 3 methods */

    public function testVariables_Blind01()  { $this->generic_test('Variables_Blind.01'); }
    public function testVariables_Blind02()  { $this->generic_test('Variables_Blind.02'); }
    public function testVariables_Blind03()  { $this->generic_test('Variables_Blind.03'); }
}
?>