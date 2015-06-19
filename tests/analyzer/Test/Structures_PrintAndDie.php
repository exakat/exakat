<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_PrintAndDie extends Analyzer {
    /* 1 methods */

    public function testStructures_PrintAndDie01()  { $this->generic_test('Structures_PrintAndDie.01'); }
}
?>