<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Php74NewDirective extends Analyzer {
    /* 1 methods */

    public function testPhp_Php74NewDirective01()  { $this->generic_test('Php/Php74NewDirective.01'); }
}
?>