<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_PrintfArguments extends Analyzer {
    /* 3 methods */

    public function testStructures_PrintfArguments01()  { $this->generic_test('Structures/PrintfArguments.01'); }
    public function testStructures_PrintfArguments02()  { $this->generic_test('Structures/PrintfArguments.02'); }
    public function testStructures_PrintfArguments03()  { $this->generic_test('Structures/PrintfArguments.03'); }
}
?>