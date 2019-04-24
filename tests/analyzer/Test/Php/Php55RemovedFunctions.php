<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Php55RemovedFunctions extends Analyzer {
    /* 1 methods */

    public function testPhp_Php55RemovedFunctions01()  { $this->generic_test('Php/Php55RemovedFunctions.01'); }
}
?>