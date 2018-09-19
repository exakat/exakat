<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class DynamicPropertyCall extends Analyzer {
    /* 3 methods */

    public function testClasses_DynamicPropertyCall01()  { $this->generic_test('Classes_DynamicPropertyCall.01'); }
    public function testClasses_DynamicPropertyCall02()  { $this->generic_test('Classes_DynamicPropertyCall.02'); }
    public function testClasses_DynamicPropertyCall03()  { $this->generic_test('Classes_DynamicPropertyCall.03'); }
}
?>