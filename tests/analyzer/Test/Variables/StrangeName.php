<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Variables_StrangeName extends Analyzer {
    /* 4 methods */

    public function testVariables_StrangeName01()  { $this->generic_test('Variables/StrangeName.01'); }
    public function testVariables_StrangeName02()  { $this->generic_test('Variables/StrangeName.02'); }
    public function testVariables_StrangeName03()  { $this->generic_test('Variables/StrangeName.03'); }
    public function testVariables_StrangeName04()  { $this->generic_test('Variables/StrangeName.04'); }
}
?>