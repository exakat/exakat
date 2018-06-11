<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_UnusedPrivateProperty extends Analyzer {
    /* 4 methods */

    public function testClasses_UnusedPrivateProperty01()  { $this->generic_test('Classes_UnusedPrivateProperty.01'); }
    public function testClasses_UnusedPrivateProperty02()  { $this->generic_test('Classes_UnusedPrivateProperty.02'); }
    public function testClasses_UnusedPrivateProperty03()  { $this->generic_test('Classes/UnusedPrivateProperty.03'); }
    public function testClasses_UnusedPrivateProperty04()  { $this->generic_test('Classes/UnusedPrivateProperty.04'); }
}
?>