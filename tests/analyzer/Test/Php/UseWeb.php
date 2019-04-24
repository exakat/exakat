<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseWeb extends Analyzer {
    /* 1 methods */

    public function testPhp_UseWeb01()  { $this->generic_test('Php/UseWeb.01'); }
}
?>