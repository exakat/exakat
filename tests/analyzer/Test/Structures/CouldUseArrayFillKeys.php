<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_CouldUseArrayFillKeys extends Analyzer {
    /* 2 methods */

    public function testStructures_CouldUseArrayFillKeys01()  { $this->generic_test('Structures/CouldUseArrayFillKeys.01'); }
    public function testStructures_CouldUseArrayFillKeys02()  { $this->generic_test('Structures/CouldUseArrayFillKeys.02'); }
}
?>