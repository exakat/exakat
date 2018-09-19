<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class PrintfArguments extends Analyzer {
    /* 6 methods */

    public function testStructures_PrintfArguments01()  { $this->generic_test('Structures/PrintfArguments.01'); }
    public function testStructures_PrintfArguments02()  { $this->generic_test('Structures/PrintfArguments.02'); }
    public function testStructures_PrintfArguments03()  { $this->generic_test('Structures/PrintfArguments.03'); }
    public function testStructures_PrintfArguments04()  { $this->generic_test('Structures/PrintfArguments.04'); }
    public function testStructures_PrintfArguments05()  { $this->generic_test('Structures/PrintfArguments.05'); }
    public function testStructures_PrintfArguments06()  { $this->generic_test('Structures/PrintfArguments.06'); }
}
?>