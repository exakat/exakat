<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UndeclaredStaticProperty extends Analyzer {
    /* 4 methods */

    public function testClasses_UndeclaredStaticProperty01()  { $this->generic_test('Classes/UndeclaredStaticProperty.01'); }
    public function testClasses_UndeclaredStaticProperty02()  { $this->generic_test('Classes/UndeclaredStaticProperty.02'); }
    public function testClasses_UndeclaredStaticProperty03()  { $this->generic_test('Classes/UndeclaredStaticProperty.03'); }
    public function testClasses_UndeclaredStaticProperty04()  { $this->generic_test('Classes/UndeclaredStaticProperty.04'); }
}
?>