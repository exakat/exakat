<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UsingThisOutsideAClass extends Analyzer {
    /* 1 methods */

    public function testClasses_UsingThisOutsideAClass01()  { $this->generic_test('Classes/UsingThisOutsideAClass.01'); }
}
?>