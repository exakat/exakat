<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UseCli extends Analyzer {
    /* 1 methods */

    public function testPhp_UseCli01()  { $this->generic_test('Php/UseCli.01'); }
}
?>