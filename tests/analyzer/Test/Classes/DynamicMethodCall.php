<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class DynamicMethodCall extends Analyzer {
    /* 4 methods */

    public function testClasses_DynamicMethodCall01()  { $this->generic_test('Classes_DynamicMethodCall.01'); }
    public function testClasses_DynamicMethodCall02()  { $this->generic_test('Classes/DynamicMethodCall.02'); }
    public function testClasses_DynamicMethodCall03()  { $this->generic_test('Classes/DynamicMethodCall.03'); }
    public function testClasses_DynamicMethodCall04()  { $this->generic_test('Classes/DynamicMethodCall.04'); }
}
?>