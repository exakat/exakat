<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class InconsistentUsage extends Analyzer {
    /* 5 methods */

    public function testVariables_InconsistentUsage01()  { $this->generic_test('Variables/InconsistentUsage.01'); }
    public function testVariables_InconsistentUsage02()  { $this->generic_test('Variables/InconsistentUsage.02'); }
    public function testVariables_InconsistentUsage03()  { $this->generic_test('Variables/InconsistentUsage.03'); }
    public function testVariables_InconsistentUsage04()  { $this->generic_test('Variables/InconsistentUsage.04'); }
    public function testVariables_InconsistentUsage05()  { $this->generic_test('Variables/InconsistentUsage.05'); }
}
?>