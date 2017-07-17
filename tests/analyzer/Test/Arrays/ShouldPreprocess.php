<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Arrays_ShouldPreprocess extends Analyzer {
    /* 9 methods */

    public function testArrays_ShouldPreprocess01()  { $this->generic_test('Arrays/ShouldPreprocess.01'); }
    public function testArrays_ShouldPreprocess02()  { $this->generic_test('Arrays/ShouldPreprocess.02'); }
    public function testArrays_ShouldPreprocess03()  { $this->generic_test('Arrays/ShouldPreprocess.03'); }
    public function testArrays_ShouldPreprocess04()  { $this->generic_test('Arrays/ShouldPreprocess.04'); }
    public function testArrays_ShouldPreprocess05()  { $this->generic_test('Arrays/ShouldPreprocess.05'); }
    public function testArrays_ShouldPreprocess06()  { $this->generic_test('Arrays/ShouldPreprocess.06'); }
    public function testArrays_ShouldPreprocess07()  { $this->generic_test('Arrays/ShouldPreprocess.07'); }
    public function testArrays_ShouldPreprocess08()  { $this->generic_test('Arrays/ShouldPreprocess.08'); }
    public function testArrays_ShouldPreprocess09()  { $this->generic_test('Arrays/ShouldPreprocess.09'); }
}
?>