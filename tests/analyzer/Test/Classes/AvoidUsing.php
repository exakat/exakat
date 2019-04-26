<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AvoidUsing extends Analyzer {
    /* 1 methods */

    public function testClasses_AvoidUsing01()  { $this->generic_test('Classes_AvoidUsing.01'); }
}
?>