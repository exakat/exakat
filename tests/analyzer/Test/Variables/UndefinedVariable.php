<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Variables_UndefinedVariable extends Analyzer {
    /* 3 methods */

    public function testVariables_UndefinedVariable01()  { $this->generic_test('Variables/UndefinedVariable.01'); }
    public function testVariables_UndefinedVariable02()  { $this->generic_test('Variables/UndefinedVariable.02'); }
    public function testVariables_UndefinedVariable03()  { $this->generic_test('Variables/UndefinedVariable.03'); }
}
?>