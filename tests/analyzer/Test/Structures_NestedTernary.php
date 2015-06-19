<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_NestedTernary extends Analyzer {
    /* 1 methods */

    public function testStructures_NestedTernary01()  { $this->generic_test('Structures_NestedTernary.01'); }
}
?>