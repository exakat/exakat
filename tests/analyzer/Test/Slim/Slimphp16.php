<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Slim_Slimphp16 extends Analyzer {
    /* 1 methods */

    public function testSlim_Slimphp1601()  { $this->generic_test('Slim/Slimphp16.01'); }
}
?>