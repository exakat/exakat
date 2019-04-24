<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class StaticVariables extends Analyzer {
    /* 4 methods */

    public function testVariables_StaticVariables01()  { $this->generic_test('Variables_StaticVariables.01'); }

    public function testVariables_StaticVariables02()  { $this->generic_test('Variables_StaticVariables.02'); }
    public function testVariables_StaticVariables03()  { $this->generic_test('Variables_StaticVariables.03'); }
    public function testVariables_StaticVariables04()  { $this->generic_test('Variables_StaticVariables.04'); }
}
?>