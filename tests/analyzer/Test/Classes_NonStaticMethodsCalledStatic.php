<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_NonStaticMethodsCalledStatic extends Analyzer {
    /* 3 methods */

    public function testClasses_NonStaticMethodsCalledStatic01()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.01'); }
    public function testClasses_NonStaticMethodsCalledStatic02()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.02'); }
    public function testClasses_NonStaticMethodsCalledStatic03()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.03'); }
}
?>