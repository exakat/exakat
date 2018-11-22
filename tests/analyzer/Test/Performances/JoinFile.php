<?php

namespace Test\Performances;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class JoinFile extends Analyzer {
    /* 2 methods */

    public function testPerformances_JoinFile01()  { $this->generic_test('Performances/JoinFile.01'); }
    public function testPerformances_JoinFile02()  { $this->generic_test('Performances/JoinFile.02'); }
}
?>