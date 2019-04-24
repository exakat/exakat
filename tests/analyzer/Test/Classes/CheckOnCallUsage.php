<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CheckOnCallUsage extends Analyzer {
    /* 1 methods */

    public function testClasses_CheckOnCallUsage01()  { $this->generic_test('Classes/CheckOnCallUsage.01'); }
}
?>