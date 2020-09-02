<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldBeParentMethod extends Analyzer {
    /* 1 methods */

    public function testClasses_CouldBeParentMethod01()  { $this->generic_test('Classes/CouldBeParentMethod.01'); }
}
?>