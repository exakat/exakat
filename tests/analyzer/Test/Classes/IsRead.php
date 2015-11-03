<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_IsRead extends Analyzer {
    /* 4 methods */

    public function testClasses_IsRead01()  { $this->generic_test('Classes_IsRead.01'); }
    public function testClasses_IsRead02()  { $this->generic_test('Classes_IsRead.02'); }
    public function testClasses_IsRead03()  { $this->generic_test('Classes_IsRead.03'); }
    public function testClasses_IsRead04()  { $this->generic_test('Classes_IsRead.04'); }
}
?>