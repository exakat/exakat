<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Variables_VariableUsedOnce extends Analyzer {
    /* 5 methods */

    public function testVariables_VariableUsedOnce01()  { $this->generic_test('Variables_VariableUsedOnce.01'); }
    public function testVariables_VariableUsedOnce02()  { $this->generic_test('Variables_VariableUsedOnce.02'); }
    public function testVariables_VariableUsedOnce03()  { $this->generic_test('Variables_VariableUsedOnce.03'); }
    public function testVariables_VariableUsedOnce04()  { $this->generic_test('Variables_VariableUsedOnce.04'); }
    public function testVariables_VariableUsedOnce05()  { $this->generic_test('Variables_VariableUsedOnce.05'); }
}
?>