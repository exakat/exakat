<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DynamicSelfCalls extends Analyzer {
    /* 1 methods */

    public function testClasses_DynamicSelfCalls01()  { $this->generic_test('Classes/DynamicSelfCalls.01'); }
}
?>