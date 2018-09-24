<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_OnlyVariableForReference extends Analyzer {
    /* 2 methods */

    public function testFunctions_OnlyVariableForReference01()  { $this->generic_test('Functions/OnlyVariableForReference.01'); }
    public function testFunctions_OnlyVariableForReference02()  { $this->generic_test('Functions/OnlyVariableForReference.02'); }
}
?>