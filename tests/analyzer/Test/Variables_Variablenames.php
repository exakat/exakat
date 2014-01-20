<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Variables_Variablenames extends Analyzer {
    /* 8 methods */

    public function testVariables_Variablenames01()  { $this->generic_test('Variables_Variablenames.01'); }
    public function testVariables_Variablenames02()  { $this->generic_test('Variables_Variablenames.02'); }
    public function testVariables_Variablenames03()  { $this->generic_test('Variables_Variablenames.03'); }
    public function testVariables_Variablenames04()  { $this->generic_test('Variables_Variablenames.04'); }
    public function testVariables_Variablenames05()  { $this->generic_test('Variables_Variablenames.05'); }
    public function testVariables_Variablenames06()  { $this->generic_test('Variables_Variablenames.06'); }
    public function testVariables_Variablenames07()  { $this->generic_test('Variables_Variablenames.07'); }
    public function testVariables_Variablenames08()  { $this->generic_test('Variables_Variablenames.08'); }
}
?>