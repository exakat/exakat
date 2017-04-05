<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Slim_Slimphp21 extends Analyzer {
    /* 1 methods */

    public function testSlim_Slimphp2101()  { $this->generic_test('Slim/Slimphp21.01'); }
}
?>