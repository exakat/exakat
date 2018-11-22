<?php

namespace Test\Slim;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Slimphp38 extends Analyzer {
    /* 1 methods */

    public function testSlim_Slimphp3801()  { $this->generic_test('Slim/Slimphp38.01'); }
}
?>