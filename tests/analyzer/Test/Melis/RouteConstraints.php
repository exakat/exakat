<?php

namespace Test\Melis;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class RouteConstraints extends Analyzer {
    /* 2 methods */

    public function testMelis_RouteConstraints01()  { $this->generic_test('Melis/RouteConstraints.01'); }
    public function testMelis_RouteConstraints02()  { $this->generic_test('Melis/RouteConstraints.02'); }
}
?>