<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_PropertyNeverUsed extends Analyzer {
    /* 4 methods */

    public function testClasses_PropertyNeverUsed01()  { $this->generic_test('Classes_PropertyNeverUsed.01'); }
    public function testClasses_PropertyNeverUsed02()  { $this->generic_test('Classes_PropertyNeverUsed.02'); }
    public function testClasses_PropertyNeverUsed03()  { $this->generic_test('Classes_PropertyNeverUsed.03'); }
    public function testClasses_PropertyNeverUsed04()  { $this->generic_test('Classes_PropertyNeverUsed.04'); }
}
?>