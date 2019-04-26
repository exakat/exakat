<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DynamicNew extends Analyzer {
    /* 1 methods */

    public function testClasses_DynamicNew01()  { $this->generic_test('Classes_DynamicNew.01'); }
}
?>