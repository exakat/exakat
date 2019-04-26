<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DetectCurrentClass extends Analyzer {
    /* 1 methods */

    public function testPhp_DetectCurrentClass01()  { $this->generic_test('Php/DetectCurrentClass.01'); }
}
?>