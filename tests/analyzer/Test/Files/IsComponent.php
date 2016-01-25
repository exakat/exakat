<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Files_IsComponent extends Analyzer {
    /* 5 methods */

    public function testFiles_IsComponent01()  { $this->generic_test('Files/IsComponent.01'); }
    public function testFiles_IsComponent02()  { $this->generic_test('Files/IsComponent.02'); }
    public function testFiles_IsComponent03()  { $this->generic_test('Files/IsComponent.03'); }
    public function testFiles_IsComponent04()  { $this->generic_test('Files/IsComponent.04'); }
    public function testFiles_IsComponent05()  { $this->generic_test('Files/IsComponent.05'); }
}
?>