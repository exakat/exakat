<?php

namespace Test\Slim;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UsedRoutes extends Analyzer {
    /* 2 methods */

    public function testSlim_UsedRoutes01()  { $this->generic_test('Slim/UsedRoutes.01'); }
    public function testSlim_UsedRoutes02()  { $this->generic_test('Slim/UsedRoutes.02'); }
}
?>