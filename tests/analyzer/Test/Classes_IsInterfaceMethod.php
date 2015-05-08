<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_IsInterfaceMethod extends Analyzer {
    /* 3 methods */

    public function testClasses_IsInterfaceMethod01()  { $this->generic_test('Classes_IsInterfaceMethod.01'); }
    public function testClasses_IsInterfaceMethod02()  { $this->generic_test('Classes_IsInterfaceMethod.02'); }
    public function testClasses_IsInterfaceMethod03()  { $this->generic_test('Classes_IsInterfaceMethod.03'); }
}
?>