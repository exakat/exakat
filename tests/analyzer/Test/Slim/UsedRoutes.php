<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Slim_UsedRoutes extends Analyzer {
    /* 2 methods */

    public function testSlim_UsedRoutes01()  { $this->generic_test('Slim/UsedRoutes.01'); }
    public function testSlim_UsedRoutes02()  { $this->generic_test('Slim/UsedRoutes.02'); }
}
?>