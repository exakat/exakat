<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CouldBeProtectedConstant extends Analyzer {
    /* 1 methods */

    public function testClasses_CouldBeProtectedConstant01()  { $this->generic_test('Classes/CouldBeProtectedConstant.01'); }
}
?>