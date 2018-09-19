<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class PropertyUsedAbove extends Analyzer {
    /* 5 methods */

    public function testClasses_PropertyUsedAbove01()  { $this->generic_test('Classes/PropertyUsedAbove.01'); }
    public function testClasses_PropertyUsedAbove02()  { $this->generic_test('Classes/PropertyUsedAbove.02'); }
    public function testClasses_PropertyUsedAbove03()  { $this->generic_test('Classes/PropertyUsedAbove.03'); }
    public function testClasses_PropertyUsedAbove04()  { $this->generic_test('Classes/PropertyUsedAbove.04'); }
    public function testClasses_PropertyUsedAbove05()  { $this->generic_test('Classes/PropertyUsedAbove.05'); }
}
?>