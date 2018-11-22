<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class StrangeName extends Analyzer {
    /* 1 methods */

    public function testClasses_StrangeName01()  { $this->generic_test('Classes/StrangeName.01'); }
}
?>