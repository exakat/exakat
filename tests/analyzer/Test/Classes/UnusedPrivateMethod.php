<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UnusedPrivateMethod extends Analyzer {
    /* 3 methods */

    public function testClasses_UnusedPrivateMethod01()  { $this->generic_test('Classes_UnusedPrivateMethod.01'); }
    public function testClasses_UnusedPrivateMethod02()  { $this->generic_test('Classes/UnusedPrivateMethod.02'); }
    public function testClasses_UnusedPrivateMethod03()  { $this->generic_test('Classes/UnusedPrivateMethod.03'); }
}
?>