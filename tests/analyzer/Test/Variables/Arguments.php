<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Variables_Arguments extends Analyzer {
    /* 5 methods */

    public function testVariables_Arguments01()  { $this->generic_test('Variables_Arguments.01'); }
    public function testVariables_Arguments02()  { $this->generic_test('Variables/Arguments.02'); }
    public function testVariables_Arguments03()  { $this->generic_test('Variables/Arguments.03'); }
    public function testVariables_Arguments04()  { $this->generic_test('Variables/Arguments.04'); }
    public function testVariables_Arguments05()  { $this->generic_test('Variables/Arguments.05'); }
}
?>