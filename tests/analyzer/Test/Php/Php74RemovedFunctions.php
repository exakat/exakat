<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Php74RemovedFunctions extends Analyzer {
    /* 2 methods */

    public function testPhp_Php74RemovedFunctions01()  { $this->generic_test('Php/Php74RemovedFunctions.01'); }
    public function testPhp_Php74RemovedFunctions02()  { $this->generic_test('Php/Php74RemovedFunctions.02'); }
}
?>