<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UseStdclass extends Analyzer {
    /* 1 methods */

    public function testPhp_UseStdclass01()  { $this->generic_test('Php/UseStdclass.01'); }
}
?>