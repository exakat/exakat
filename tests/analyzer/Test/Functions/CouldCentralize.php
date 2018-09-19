<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class CouldCentralize extends Analyzer {
    /* 4 methods */

    public function testFunctions_CouldCentralize01()  { $this->generic_test('Functions/CouldCentralize.01'); }
    public function testFunctions_CouldCentralize02()  { $this->generic_test('Functions/CouldCentralize.02'); }
    public function testFunctions_CouldCentralize03()  { $this->generic_test('Functions/CouldCentralize.03'); }
    public function testFunctions_CouldCentralize04()  { $this->generic_test('Functions/CouldCentralize.04'); }
}
?>