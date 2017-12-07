<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Variables_UncommonEnvVar extends Analyzer {
    /* 2 methods */

    public function testVariables_UncommonEnvVar01()  { $this->generic_test('Variables/UncommonEnvVar.01'); }
    public function testVariables_UncommonEnvVar02()  { $this->generic_test('Variables/UncommonEnvVar.02'); }
}
?>