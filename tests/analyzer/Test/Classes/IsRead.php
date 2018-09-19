<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class IsRead extends Analyzer {
    /* 6 methods */

    public function testClasses_IsRead01()  { $this->generic_test('Classes_IsRead.01'); }
    public function testClasses_IsRead02()  { $this->generic_test('Classes_IsRead.02'); }
    public function testClasses_IsRead03()  { $this->generic_test('Classes_IsRead.03'); }
    public function testClasses_IsRead04()  { $this->generic_test('Classes_IsRead.04'); }
    public function testClasses_IsRead05()  { $this->generic_test('Classes/IsRead.05'); }
    public function testClasses_IsRead06()  { $this->generic_test('Classes/IsRead.06'); }
}
?>