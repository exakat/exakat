<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_DynamicMethodCall extends Analyzer {
    /* 3 methods */

    public function testClasses_DynamicMethodCall01()  { $this->generic_test('Classes_DynamicMethodCall.01'); }
    public function testClasses_DynamicMethodCall02()  { $this->generic_test('Classes/DynamicMethodCall.02'); }
    public function testClasses_DynamicMethodCall03()  { $this->generic_test('Classes/DynamicMethodCall.03'); }
}
?>