<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Abstractmethods extends Analyzer {
    /* 1 methods */

    public function testClasses_Abstractmethods01()  { $this->generic_test('Classes_Abstractmethods.01'); }
}
?>