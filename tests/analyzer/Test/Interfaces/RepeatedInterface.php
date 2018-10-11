<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class RepeatedInterface extends Analyzer {
    /* 2 methods */

    public function testInterfaces_RepeatedInterface01()  { $this->generic_test('Interfaces/RepeatedInterface.01'); }
    public function testInterfaces_RepeatedInterface02()  { $this->generic_test('Interfaces/RepeatedInterface.02'); }
}
?>