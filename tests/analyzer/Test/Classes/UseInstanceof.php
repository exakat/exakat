<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UseInstanceof extends Analyzer {
    /* 1 methods */

    public function testClasses_UseInstanceof01()  { $this->generic_test('Classes/UseInstanceof.01'); }
}
?>