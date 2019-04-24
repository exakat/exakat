<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Gotonames extends Analyzer {
    /* 1 methods */

    public function testPhp_Gotonames01()  { $this->generic_test('Php/Gotonames.01'); }
}
?>