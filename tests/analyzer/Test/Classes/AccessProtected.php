<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AccessProtected extends Analyzer {
    /* 1 methods */

    public function testClasses_AccessProtected01()  { $this->generic_test('Classes/AccessProtected.01'); }
}
?>