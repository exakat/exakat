<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Php70RemovedFunctions extends Analyzer {
    /* 2 methods */

    public function testPhp_Php70RemovedFunctions01()  { $this->generic_test('Php/Php70RemovedFunctions.01'); }
    public function testPhp_Php70RemovedFunctions02()  { $this->generic_test('Php/Php70RemovedFunctions.02'); }
}
?>