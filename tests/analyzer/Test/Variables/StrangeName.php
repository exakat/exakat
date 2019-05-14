<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class StrangeName extends Analyzer {
    /* 7 methods */

    public function testVariables_StrangeName01()  { $this->generic_test('Variables/StrangeName.01'); }
    public function testVariables_StrangeName02()  { $this->generic_test('Variables/StrangeName.02'); }
    public function testVariables_StrangeName03()  { $this->generic_test('Variables/StrangeName.03'); }
    public function testVariables_StrangeName04()  { $this->generic_test('Variables/StrangeName.04'); }
    public function testVariables_StrangeName05()  { $this->generic_test('Variables/StrangeName.05'); }
    public function testVariables_StrangeName06()  { $this->generic_test('Variables/StrangeName.06'); }
    public function testVariables_StrangeName07()  { $this->generic_test('Variables/StrangeName.07'); }
}
?>