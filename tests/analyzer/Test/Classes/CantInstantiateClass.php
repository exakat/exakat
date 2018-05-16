<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_CantInstantiateClass extends Analyzer {
    /* 3 methods */

    public function testClasses_CantInstantiateClass01()  { $this->generic_test('Classes/CantInstantiateClass.01'); }
    public function testClasses_CantInstantiateClass02()  { $this->generic_test('Classes/CantInstantiateClass.02'); }
    public function testClasses_CantInstantiateClass03()  { $this->generic_test('Classes/CantInstantiateClass.03'); }
}
?>