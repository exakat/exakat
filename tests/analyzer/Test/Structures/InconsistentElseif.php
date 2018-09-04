<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_InconsistentElseif extends Analyzer {
    /* 2 methods */

    public function testStructures_InconsistentElseif01()  { $this->generic_test('Structures/InconsistentElseif.01'); }
    public function testStructures_InconsistentElseif02()  { $this->generic_test('Structures/InconsistentElseif.02'); }
}
?>