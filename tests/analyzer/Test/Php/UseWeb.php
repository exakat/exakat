<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UseWeb extends Analyzer {
    /* 1 methods */

    public function testPhp_UseWeb01()  { $this->generic_test('Php/UseWeb.01'); }
}
?>