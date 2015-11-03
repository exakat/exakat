<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Variables_VariableUsedOnceByContext extends Analyzer {
    /* 6 methods */

    public function testVariables_VariableUsedOnceByContext01()  { $this->generic_test('Variables_VariableUsedOnceByContext.01'); }
    public function testVariables_VariableUsedOnceByContext02()  { $this->generic_test('Variables_VariableUsedOnceByContext.02'); }
    public function testVariables_VariableUsedOnceByContext03()  { $this->generic_test('Variables_VariableUsedOnceByContext.03'); }
    public function testVariables_VariableUsedOnceByContext04()  { $this->generic_test('Variables_VariableUsedOnceByContext.04'); }
    public function testVariables_VariableUsedOnceByContext05()  { $this->generic_test('Variables_VariableUsedOnceByContext.05'); }
    public function testVariables_VariableUsedOnceByContext06()  { $this->generic_test('Variables_VariableUsedOnceByContext.06'); }
}
?>