<?php

namespace Test\Slim;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoEchoInRouteCallable extends Analyzer {
    /* 2 methods */

    public function testSlim_NoEchoInRouteCallable01()  { $this->generic_test('Slim/NoEchoInRouteCallable.01'); }
    public function testSlim_NoEchoInRouteCallable02()  { $this->generic_test('Slim/NoEchoInRouteCallable.02'); }
}
?>