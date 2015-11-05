<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Variables_InterfaceArguments extends Analyzer {
    /* 3 methods */

    public function testVariables_InterfaceArguments01()  { $this->generic_test('Variables_InterfaceArguments.01'); }
    public function testVariables_InterfaceArguments02()  { $this->generic_test('Variables_InterfaceArguments.02'); }
    public function testVariables_InterfaceArguments03()  { $this->generic_test('Variables/InterfaceArguments.03'); }
}
?>