<?php

namespace Test\Performances;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class LogicalToInArray extends Analyzer {
    /* 3 methods */

    public function testPerformances_LogicalToInArray01()  { $this->generic_test('Performances/LogicalToInArray.01'); }
    public function testPerformances_LogicalToInArray02()  { $this->generic_test('Performances/LogicalToInArray.02'); }
    public function testPerformances_LogicalToInArray03()  { $this->generic_test('Performances/LogicalToInArray.03'); }
}
?>