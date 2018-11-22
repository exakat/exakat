<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Classnames extends Analyzer {
    /* 2 methods */

    public function testClasses_Classnames01()  { $this->generic_test('Classes_Classnames.01'); }
    public function testClasses_Classnames02()  { $this->generic_test('Classes/Classnames.02'); }
}
?>