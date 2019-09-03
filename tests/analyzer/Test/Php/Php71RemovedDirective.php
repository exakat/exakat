<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Php71RemovedDirective extends Analyzer {
    /* 2 methods */

    public function testPhp_Php71RemovedDirective01()  { $this->generic_test('Php/Php71RemovedDirective.01'); }
    public function testPhp_Php71RemovedDirective02()  { $this->generic_test('Php/Php71RemovedDirective.02'); }
}
?>