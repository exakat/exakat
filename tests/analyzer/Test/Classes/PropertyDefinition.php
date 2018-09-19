<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class PropertyDefinition extends Analyzer {
    /* 1 methods */

    public function testClasses_PropertyDefinition01()  { $this->generic_test('Classes_PropertyDefinition.01'); }
    public function testClasses_PropertyDefinition02()  { $this->generic_test('Classes_PropertyDefinition.02'); }
}
?>