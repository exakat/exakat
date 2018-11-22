<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NormalProperty extends Analyzer {
    /* 1 methods */

    public function testClasses_NormalProperty01()  { $this->generic_test('Classes/NormalProperty.01'); }
}
?>