<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Variables_Variablenames extends Analyzer {
    /* 2 methods */

    public function testVariables_Variablenames01()  { $this->generic_test('Variables_Variablenames.01'); }
    public function testVariables_Variablenames02()  { $this->generic_test('Variables_Variablenames.02'); }
}
?>