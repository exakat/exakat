<?php

namespace Test\Project;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class IsLibrary extends Analyzer {
    /* 5 methods */

    public function testProject_IsLibrary01()  { $this->generic_test('Project/IsLibrary.01'); }
    public function testProject_IsLibrary02()  { $this->generic_test('Project/IsLibrary.02'); }
    public function testProject_IsLibrary03()  { $this->generic_test('Project/IsLibrary.03'); }
    public function testProject_IsLibrary04()  { $this->generic_test('Project/IsLibrary.04'); }
    public function testProject_IsLibrary05()  { $this->generic_test('Project/IsLibrary.05'); }
}
?>