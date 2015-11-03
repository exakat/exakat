<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_UsedClass extends Analyzer {
    /* 2 methods */

    public function testClasses_UsedClass01()  { $this->generic_test('Classes_UsedClass.01'); }
    public function testClasses_UsedClass02()  { $this->generic_test('Classes_UsedClass.02'); }
}
?>