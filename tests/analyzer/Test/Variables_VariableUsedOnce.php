<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Variables_VariableUsedOnce extends Analyzer {
    /* 11 methods */

    public function testVariables_VariableUsedOnce01()  { $this->generic_test('Variables_VariableUsedOnce.01'); }
    public function testVariables_VariableUsedOnce02()  { $this->generic_test('Variables_VariableUsedOnce.02'); }
    public function testVariables_VariableUsedOnce03()  { $this->generic_test('Variables_VariableUsedOnce.03'); }
    public function testVariables_VariableUsedOnce04()  { $this->generic_test('Variables_VariableUsedOnce.04'); }
    public function testVariables_VariableUsedOnce05()  { $this->generic_test('Variables_VariableUsedOnce.05'); }
    public function testVariables_VariableUsedOnce06()  { $this->generic_test('Variables_VariableUsedOnce.06'); }
    public function testVariables_VariableUsedOnce07()  { $this->generic_test('Variables_VariableUsedOnce.07'); }
    public function testVariables_VariableUsedOnce08()  { $this->generic_test('Variables_VariableUsedOnce.08'); }
    public function testVariables_VariableUsedOnce09()  { $this->generic_test('Variables_VariableUsedOnce.09'); }
    public function testVariables_VariableUsedOnce10()  { $this->generic_test('Variables_VariableUsedOnce.10'); }
    public function testVariables_VariableUsedOnce11()  { $this->generic_test('Variables_VariableUsedOnce.11'); }
}
?>