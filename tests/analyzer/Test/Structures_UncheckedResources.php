<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_UncheckedResources extends Analyzer {
    /* 2 methods */

    public function testStructures_UncheckedResources01()  { $this->generic_test('Structures_UncheckedResources.01'); }
    public function testStructures_UncheckedResources02()  { $this->generic_test('Structures_UncheckedResources.02'); }
}
?>