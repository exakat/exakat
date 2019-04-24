<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CaseForPSS extends Analyzer {
    /* 1 methods */

    public function testPhp_CaseForPSS01()  { $this->generic_test('Php/CaseForPSS.01'); }
}
?>