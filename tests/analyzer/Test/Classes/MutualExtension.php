<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class MutualExtension extends Analyzer {
    /* 1 methods */

    public function testClasses_MutualExtension01()  { $this->generic_test('Classes_MutualExtension.01'); }
}
?>