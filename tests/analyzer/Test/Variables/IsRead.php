<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IsRead extends Analyzer {
    /* 8 methods */

    public function testVariables_IsRead01()  { $this->generic_test('Variables_IsRead.01'); }
    public function testVariables_IsRead02()  { $this->generic_test('Variables_IsRead.02'); }
    public function testVariables_IsRead03()  { $this->generic_test('Variables_IsRead.03'); }
    public function testVariables_IsRead04()  { $this->generic_test('Variables_IsRead.04'); }
    public function testVariables_IsRead05()  { $this->generic_test('Variables_IsRead.05'); }
    public function testVariables_IsRead06()  { $this->generic_test('Variables/IsRead.06'); }
    public function testVariables_IsRead07()  { $this->generic_test('Variables/IsRead.07'); }
    public function testVariables_IsRead08()  { $this->generic_test('Variables/IsRead.08'); }
}
?>