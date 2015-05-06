<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_LoneBlock extends Analyzer {
    /* 3 methods */

    public function testStructures_LoneBlock01()  { $this->generic_test('Structures_LoneBlock.01'); }
    public function testStructures_LoneBlock02()  { $this->generic_test('Structures_LoneBlock.02'); }
    public function testStructures_LoneBlock03()  { $this->generic_test('Structures_LoneBlock.03'); }
}
?>