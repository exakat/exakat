<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DependantAbstractClass extends Analyzer {
    /* 2 methods */

    public function testClasses_DependantAbstractClass01()  { $this->generic_test('Classes/DependantAbstractClass.01'); }
    public function testClasses_DependantAbstractClass02()  { $this->generic_test('Classes/DependantAbstractClass.02'); }
}
?>