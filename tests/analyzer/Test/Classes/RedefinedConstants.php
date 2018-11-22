<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class RedefinedConstants extends Analyzer {
    /* 1 methods */

    public function testClasses_RedefinedConstants01()  { $this->generic_test('Classes/RedefinedConstants.01'); }
}
?>