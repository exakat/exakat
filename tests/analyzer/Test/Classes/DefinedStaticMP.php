<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DefinedStaticMP extends Analyzer {
    /* 2 methods */

    public function testClasses_DefinedStaticMP01()  { $this->generic_test('Classes_DefinedStaticMP.01'); }
    public function testClasses_DefinedStaticMP02()  { $this->generic_test('Classes_DefinedStaticMP.02'); }
}
?>