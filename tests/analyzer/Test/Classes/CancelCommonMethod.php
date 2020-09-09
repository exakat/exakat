<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CancelCommonMethod extends Analyzer {
    /* 1 methods */

    public function testClasses_CancelCommonMethod01()  { $this->generic_test('Classes/CancelCommonMethod.01'); }
}
?>