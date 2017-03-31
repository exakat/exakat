<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Slim_Slimphp12 extends Analyzer {
    /* 1 methods */

    public function testSlim_Slimphp1201()  { $this->generic_test('Slim/Slimphp12.01'); }
}
?>