<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_NoDirectUsage extends Analyzer {
    /* 3 methods */

    public function testStructures_NoDirectUsage01()  { $this->generic_test('Structures_NoDirectUsage.01'); }
    public function testStructures_NoDirectUsage02()  { $this->generic_test('Structures_NoDirectUsage.02'); }
    public function testStructures_NoDirectUsage03()  { $this->generic_test('Structures_NoDirectUsage.03'); }
}
?>