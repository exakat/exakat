<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DetectCurrentClass extends Analyzer {
    /* 1 methods */

    public function testPhp_DetectCurrentClass01()  { $this->generic_test('Php/DetectCurrentClass.01'); }
}
?>