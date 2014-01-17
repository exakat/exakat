<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Variables_StaticVariables extends Analyzer {
    /* 2 methods */

    public function testVariables_StaticVariables01()  { $this->generic_test('Variables_StaticVariables.01'); }

    public function testVariables_StaticVariables02()  { $this->generic_test('Variables_StaticVariables.02'); }
}
?>