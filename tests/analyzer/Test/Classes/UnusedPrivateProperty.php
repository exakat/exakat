<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UnusedPrivateProperty extends Analyzer {
    /* 5 methods */

    public function testClasses_UnusedPrivateProperty01()  { $this->generic_test('Classes_UnusedPrivateProperty.01'); }
    public function testClasses_UnusedPrivateProperty02()  { $this->generic_test('Classes_UnusedPrivateProperty.02'); }
    public function testClasses_UnusedPrivateProperty03()  { $this->generic_test('Classes/UnusedPrivateProperty.03'); }
    public function testClasses_UnusedPrivateProperty04()  { $this->generic_test('Classes/UnusedPrivateProperty.04'); }
    public function testClasses_UnusedPrivateProperty05()  { $this->generic_test('Classes/UnusedPrivateProperty.05'); }
}
?>