<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Functions_Recursive extends Analyzer {
    /* 3 methods */

    public function testFunctions_Recursive01()  { $this->generic_test('Functions_Recursive.01'); }
    public function testFunctions_Recursive02()  { $this->generic_test('Functions_Recursive.02'); }
    public function testFunctions_Recursive03()  { $this->generic_test('Functions_Recursive.03'); }
}
?>