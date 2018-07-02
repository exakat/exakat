<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_UsedPrivateMethod extends Analyzer {
    /* 3 methods */

    public function testClasses_UsedPrivateMethod01()  { $this->generic_test('Classes_UsedPrivateMethod.01'); }
    public function testClasses_UsedPrivateMethod02()  { $this->generic_test('Classes_UsedPrivateMethod.02'); }
    public function testClasses_UsedPrivateMethod03()  { $this->generic_test('Classes/UsedPrivateMethod.03'); }
}
?>