<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class TestThenCast extends Analyzer {
    /* 4 methods */

    public function testStructures_TestThenCast01()  { $this->generic_test('Structures/TestThenCast.01'); }
    public function testStructures_TestThenCast02()  { $this->generic_test('Structures/TestThenCast.02'); }
    public function testStructures_TestThenCast03()  { $this->generic_test('Structures/TestThenCast.03'); }
    public function testStructures_TestThenCast04()  { $this->generic_test('Structures/TestThenCast.04'); }
}
?>