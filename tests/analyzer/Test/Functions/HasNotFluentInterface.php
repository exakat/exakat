<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class HasNotFluentInterface extends Analyzer {
    /* 1 methods */

    public function testFunctions_HasNotFluentInterface01()  { $this->generic_test('Functions_HasNotFluentInterface.01'); }
}
?>