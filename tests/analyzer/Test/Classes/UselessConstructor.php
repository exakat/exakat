<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UselessConstructor extends Analyzer {
    /* 1 methods */

    public function testClasses_UselessConstructor01()  { $this->generic_test('Classes_UselessConstructor.01'); }
}
?>