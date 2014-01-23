<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Variables_VariablePhp extends Analyzer {
    /* 1 methods */

    public function testVariables_VariablePhp01()  { $this->generic_test('Variables_VariablePhp.01'); }
}
?>