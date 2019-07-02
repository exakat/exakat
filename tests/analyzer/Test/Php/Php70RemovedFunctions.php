<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Php70RemovedFunctions extends Analyzer {
    /* 3 methods */

    public function testPhp_Php70RemovedFunctions01()  { $this->generic_test('Php/Php70RemovedFunctions.01'); }
    public function testPhp_Php70RemovedFunctions02()  { $this->generic_test('Php/Php70RemovedFunctions.02'); }
    public function testPhp_Php70RemovedFunctions03()  { $this->generic_test('Php/Php70RemovedFunctions.03'); }
}
?>