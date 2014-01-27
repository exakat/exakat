<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Interfaces_InterfaceUsage extends Analyzer {
    /* 1 methods */

    public function testInterfaces_InterfaceUsage01()  { $this->generic_test('Interfaces_InterfaceUsage.01'); }
}
?>