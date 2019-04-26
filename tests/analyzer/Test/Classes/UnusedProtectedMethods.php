<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UnusedProtectedMethods extends Analyzer {
    /* 1 methods */

    public function testClasses_UnusedProtectedMethods01()  { $this->generic_test('Classes/UnusedProtectedMethods.01'); }
}
?>