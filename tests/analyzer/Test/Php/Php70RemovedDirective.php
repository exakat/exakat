<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Php70RemovedDirective extends Analyzer {
    /* 2 methods */

    public function testPhp_Php70RemovedDirective01()  { $this->generic_test('Php/Php70RemovedDirective.01'); }
    public function testPhp_Php70RemovedDirective02()  { $this->generic_test('Php/Php70RemovedDirective.02'); }
}
?>