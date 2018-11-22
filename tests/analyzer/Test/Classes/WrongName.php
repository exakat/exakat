<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class WrongName extends Analyzer {
    /* 1 methods */

    public function testClasses_WrongName01()  { $this->generic_test('Classes/WrongName.01'); }
}
?>