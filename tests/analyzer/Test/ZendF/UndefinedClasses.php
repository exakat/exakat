<?php

namespace Test\ZendF;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UndefinedClasses extends Analyzer {
    /* 1 methods */

    public function testZendF_UndefinedClasses01()  { $this->generic_test('ZendF/UndefinedClasses.01'); }
}
?>