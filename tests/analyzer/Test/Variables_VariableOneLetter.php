<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Variables_VariableOneLetter extends Analyzer {
    /* 2 methods */

    public function testVariables_VariableOneLetter01()  { $this->generic_test('Variables_VariableOneLetter.01'); }
    public function testVariables_VariableOneLetter02()  { $this->generic_test('Variables_VariableOneLetter.02'); }
}
?>