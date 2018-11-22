<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CouldBeAbstractClass extends Analyzer {
    /* 1 methods */

    public function testClasses_CouldBeAbstractClass01()  { $this->generic_test('Classes/CouldBeAbstractClass.01'); }
}
?>