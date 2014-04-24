<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_MethodDefinition extends Analyzer {
    /* 3 methods */

    public function testClasses_MethodDefinition01()  { $this->generic_test('Classes_MethodDefinition.01'); }
    public function testClasses_MethodDefinition02()  { $this->generic_test('Classes_MethodDefinition.02'); }
    public function testClasses_MethodDefinition03()  { $this->generic_test('Classes_MethodDefinition.03'); }
}
?>