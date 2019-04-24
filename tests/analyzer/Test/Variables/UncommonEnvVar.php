<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UncommonEnvVar extends Analyzer {
    /* 2 methods */

    public function testVariables_UncommonEnvVar01()  { $this->generic_test('Variables/UncommonEnvVar.01'); }
    public function testVariables_UncommonEnvVar02()  { $this->generic_test('Variables/UncommonEnvVar.02'); }
}
?>