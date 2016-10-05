<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_UndefinedFunctions extends Analyzer {
    /* 4 methods */

    public function testFunctions_UndefinedFunctions01()  { $this->generic_test('Functions_UndefinedFunctions.01'); }
    public function testFunctions_UndefinedFunctions02()  { $this->generic_test('Functions/UndefinedFunctions.02'); }
    public function testFunctions_UndefinedFunctions03()  { $this->generic_test('Functions/UndefinedFunctions.03'); }
    public function testFunctions_UndefinedFunctions04()  { $this->generic_test('Functions/UndefinedFunctions.04'); }
}
?>