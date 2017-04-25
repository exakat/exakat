<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Arrays_EmptyFinal extends Analyzer {
    /* 5 methods */

    public function testArrays_EmptyFinal01()  { $this->generic_test('Arrays/EmptyFinal.01'); }
    public function testArrays_EmptyFinal02()  { $this->generic_test('Arrays/EmptyFinal.02'); }
    public function testArrays_EmptyFinal03()  { $this->generic_test('Arrays/EmptyFinal.03'); }
    public function testArrays_EmptyFinal04()  { $this->generic_test('Arrays/EmptyFinal.04'); }
    public function testArrays_EmptyFinal05()  { $this->generic_test('Arrays/EmptyFinal.05'); }
}
?>