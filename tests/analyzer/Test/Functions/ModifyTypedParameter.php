<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ModifyTypedParameter extends Analyzer {
    /* 1 methods */

    public function testFunctions_ModifyTypedParameter01()  { $this->generic_test('Functions/ModifyTypedParameter.01'); }
}
?>