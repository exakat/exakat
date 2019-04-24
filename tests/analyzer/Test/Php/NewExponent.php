<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NewExponent extends Analyzer {
    /* 1 methods */

    public function testPhp_NewExponent01()  { $this->generic_test('Php/NewExponent.01'); }
}
?>