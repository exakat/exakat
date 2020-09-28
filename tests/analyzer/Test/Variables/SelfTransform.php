<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SelfTransform extends Analyzer {
    /* 3 methods */

    public function testVariables_SelfTransform01()  { $this->generic_test('Variables/SelfTransform.01'); }
    public function testVariables_SelfTransform02()  { $this->generic_test('Variables/SelfTransform.02'); }
    public function testVariables_SelfTransform03()  { $this->generic_test('Variables/SelfTransform.03'); }
}
?>