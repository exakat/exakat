<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class RedefinedMethods extends Analyzer {
    /* 2 methods */

    public function testClasses_RedefinedMethods01()  { $this->generic_test('Classes/RedefinedMethods.01'); }
    public function testClasses_RedefinedMethods02()  { $this->generic_test('Classes/RedefinedMethods.02'); }
}
?>