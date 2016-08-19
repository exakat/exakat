<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_PropertyVariableConfusion extends Analyzer {
    /* 4 methods */

    public function testStructures_PropertyVariableConfusion01()  { $this->generic_test('Structures_PropertyVariableConfusion.01'); }
    public function testStructures_PropertyVariableConfusion02()  { $this->generic_test('Structures_PropertyVariableConfusion.02'); }
    public function testStructures_PropertyVariableConfusion03()  { $this->generic_test('Structures/PropertyVariableConfusion.03'); }
    public function testStructures_PropertyVariableConfusion04()  { $this->generic_test('Structures/PropertyVariableConfusion.04'); }
}
?>