<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_VardumpUsage extends Analyzer {
    /* 3 methods */

    public function testStructures_VardumpUsage01()  { $this->generic_test('Structures_VardumpUsage.01'); }
    public function testStructures_VardumpUsage02()  { $this->generic_test('Structures_VardumpUsage.02'); }
    public function testStructures_VardumpUsage03()  { $this->generic_test('Structures_VardumpUsage.03'); }
}
?>