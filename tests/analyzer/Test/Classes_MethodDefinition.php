<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Classes_MethodDefinition extends Analyzer {
    /* 2 methods */

    public function testClasses_MethodDefinition01()  { $this->generic_test('Classes_MethodDefinition.01'); }
    public function testClasses_MethodDefinition02()  { $this->generic_test('Classes_MethodDefinition.02'); }
}
?>