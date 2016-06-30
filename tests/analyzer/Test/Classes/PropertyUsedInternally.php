<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_PropertyUsedInternally extends Analyzer {
    /* 8 methods */

    public function testClasses_PropertyUsedInternally01()  { $this->generic_test('Classes_PropertyUsedInternally.01'); }
    public function testClasses_PropertyUsedInternally02()  { $this->generic_test('Classes_PropertyUsedInternally.02'); }
    public function testClasses_PropertyUsedInternally03()  { $this->generic_test('Classes_PropertyUsedInternally.03'); }
    public function testClasses_PropertyUsedInternally04()  { $this->generic_test('Classes_PropertyUsedInternally.04'); }
    public function testClasses_PropertyUsedInternally05()  { $this->generic_test('Classes/PropertyUsedInternally.05'); }
    public function testClasses_PropertyUsedInternally06()  { $this->generic_test('Classes/PropertyUsedInternally.06'); }
    public function testClasses_PropertyUsedInternally07()  { $this->generic_test('Classes/PropertyUsedInternally.07'); }
    public function testClasses_PropertyUsedInternally08()  { $this->generic_test('Classes/PropertyUsedInternally.08'); }
}
?>