<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class IsaMagicProperty extends Analyzer {
    /* 3 methods */

    public function testClasses_IsaMagicProperty01()  { $this->generic_test('Classes/IsaMagicProperty.01'); }
    public function testClasses_IsaMagicProperty02()  { $this->generic_test('Classes/IsaMagicProperty.02'); }
    public function testClasses_IsaMagicProperty03()  { $this->generic_test('Classes/IsaMagicProperty.03'); }
}
?>