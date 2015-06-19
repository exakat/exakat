<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_NoDirectAccess extends Analyzer {
    /* 3 methods */

    public function testStructures_NoDirectAccess01()  { $this->generic_test('Structures_NoDirectAccess.01'); }
    public function testStructures_NoDirectAccess02()  { $this->generic_test('Structures_NoDirectAccess.02'); }
    public function testStructures_NoDirectAccess03()  { $this->generic_test('Structures_NoDirectAccess.03'); }
}
?>