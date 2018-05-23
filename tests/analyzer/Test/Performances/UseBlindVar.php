<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Performances_UseBlindVar extends Analyzer {
    /* 2 methods */

    public function testPerformances_UseBlindVar01()  { $this->generic_test('Performances/UseBlindVar.01'); }
    public function testPerformances_UseBlindVar02()  { $this->generic_test('Performances/UseBlindVar.02'); }
}
?>