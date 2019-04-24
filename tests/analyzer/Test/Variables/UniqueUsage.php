<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UniqueUsage extends Analyzer {
    /* 2 methods */

    public function testVariables_UniqueUsage01()  { $this->generic_test('Variables/UniqueUsage.01'); }
    public function testVariables_UniqueUsage02()  { $this->generic_test('Variables/UniqueUsage.02'); }
}
?>