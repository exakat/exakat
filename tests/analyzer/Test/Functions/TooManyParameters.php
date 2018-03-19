<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_TooManyParameters extends Analyzer {
    /* 3 methods */

    public function testFunctions_TooManyParameters01()  { $this->generic_test('Functions/TooManyParameters.01'); }
    public function testFunctions_TooManyParameters02()  { $this->generic_test('Functions/TooManyParameters.02'); }
    public function testFunctions_TooManyParameters03()  { $this->generic_test('Functions/TooManyParameters.03'); }
}
?>