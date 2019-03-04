<?php

namespace Test\Variables;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class InconsistentUsage extends Analyzer {
    /* 3 methods */

    public function testVariables_InconsistentUsage01()  { $this->generic_test('Variables/InconsistentUsage.01'); }
    public function testVariables_InconsistentUsage02()  { $this->generic_test('Variables/InconsistentUsage.02'); }
    public function testVariables_InconsistentUsage03()  { $this->generic_test('Variables/InconsistentUsage.03'); }
}
?>