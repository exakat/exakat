<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Php80RemovedFunctions extends Analyzer {
    /* 1 methods */

    public function testPhp_Php80RemovedFunctions01()  { $this->generic_test('Php/Php80RemovedFunctions.01'); }
}
?>