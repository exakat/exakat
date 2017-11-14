<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_StaticMethodsCalledFromObject extends Analyzer {
    /* 4 methods */

    public function testClasses_StaticMethodsCalledFromObject01()  { $this->generic_test('Classes_StaticMethodsCalledFromObject.01'); }
    public function testClasses_StaticMethodsCalledFromObject02()  { $this->generic_test('Classes/StaticMethodsCalledFromObject.02'); }
    public function testClasses_StaticMethodsCalledFromObject03()  { $this->generic_test('Classes/StaticMethodsCalledFromObject.03'); }
    public function testClasses_StaticMethodsCalledFromObject04()  { $this->generic_test('Classes/StaticMethodsCalledFromObject.04'); }
}
?>