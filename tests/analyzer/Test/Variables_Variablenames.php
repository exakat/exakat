<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Variables_Variablenames extends Analyzer {
    /* 3 methods */

    public function testVariables_Variablenames01()  { $this->generic_test('Variables_Variablenames.01'); }
    public function testVariables_Variablenames02()  { $this->generic_test('Variables_Variablenames.02'); }
    public function testVariables_Variablenames03()  { $this->generic_test('Variables_Variablenames.03'); }
}
?>