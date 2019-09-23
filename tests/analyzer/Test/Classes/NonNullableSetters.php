<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NonNullableSetters extends Analyzer {
    /* 1 methods */

    public function testClasses_NonNullableSetters01()  { $this->generic_test('Classes/NonNullableSetters.01'); }
}
?>