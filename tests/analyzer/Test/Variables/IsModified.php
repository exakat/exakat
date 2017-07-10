<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Variables_IsModified extends Analyzer {
    /* 6 methods */

    public function testVariables_IsModified01()  { $this->generic_test('Variables_IsModified.01'); }
    public function testVariables_IsModified02()  { $this->generic_test('Variables_IsModified.02'); }
    public function testVariables_IsModified03()  { $this->generic_test('Variables_IsModified.03'); }
    public function testVariables_IsModified04()  { $this->generic_test('Variables_IsModified.04'); }
    public function testVariables_IsModified05()  { $this->generic_test('Variables_IsModified.05'); }
    public function testVariables_IsModified06()  { $this->generic_test('Variables/IsModified.06'); }
}
?>