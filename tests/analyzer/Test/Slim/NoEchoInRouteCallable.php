<?php

namespace Test\Slim;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class NoEchoInRouteCallable extends Analyzer {
    /* 2 methods */

    public function testSlim_NoEchoInRouteCallable01()  { $this->generic_test('Slim/NoEchoInRouteCallable.01'); }
    public function testSlim_NoEchoInRouteCallable02()  { $this->generic_test('Slim/NoEchoInRouteCallable.02'); }
}
?>