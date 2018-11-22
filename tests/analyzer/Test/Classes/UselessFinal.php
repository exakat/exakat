<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UselessFinal extends Analyzer {
    /* 2 methods */

    public function testClasses_UselessFinal01()  { $this->generic_test('Classes_UselessFinal.01'); }
    public function testClasses_UselessFinal02()  { $this->generic_test('Classes/UselessFinal.02'); }
}
?>