<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CloningUsage extends Analyzer {
    /* 1 methods */

    public function testClasses_CloningUsage01()  { $this->generic_test('Classes_CloningUsage.01'); }
}
?>