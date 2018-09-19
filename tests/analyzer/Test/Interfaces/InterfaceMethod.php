<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class InterfaceMethod extends Analyzer {
    /* 1 methods */

    public function testInterfaces_InterfaceMethod01()  { $this->generic_test('Interfaces_InterfaceMethod.01'); }
}
?>