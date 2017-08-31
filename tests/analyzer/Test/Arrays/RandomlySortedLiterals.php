<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Arrays_RandomlySortedLiterals extends Analyzer {
    /* 5 methods */

    public function testArrays_RandomlySortedLiterals01()  { $this->generic_test('Arrays/RandomlySortedLiterals.01'); }
    public function testArrays_RandomlySortedLiterals02()  { $this->generic_test('Arrays/RandomlySortedLiterals.02'); }
    public function testArrays_RandomlySortedLiterals03()  { $this->generic_test('Arrays/RandomlySortedLiterals.03'); }
    public function testArrays_RandomlySortedLiterals04()  { $this->generic_test('Arrays/RandomlySortedLiterals.04'); }
    public function testArrays_RandomlySortedLiterals05()  { $this->generic_test('Arrays/RandomlySortedLiterals.05'); }
}
?>