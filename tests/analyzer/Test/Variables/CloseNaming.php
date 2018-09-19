<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class CloseNaming extends Analyzer {
    /* 4 methods */

    public function testVariables_CloseNaming01()  { $this->generic_test('Variables/CloseNaming.01'); }
    public function testVariables_CloseNaming02()  { $this->generic_test('Variables/CloseNaming.02'); }
    public function testVariables_CloseNaming03()  { $this->generic_test('Variables/CloseNaming.03'); }
    public function testVariables_CloseNaming04()  { $this->generic_test('Variables/CloseNaming.04'); }
}
?>