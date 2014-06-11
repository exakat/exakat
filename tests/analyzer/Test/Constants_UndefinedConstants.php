<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Constants_UndefinedConstants extends Analyzer {
    /* 4 methods */

    public function testConstants_UndefinedConstants01()  { $this->generic_test('Constants_UndefinedConstants.01'); }
    public function testConstants_UndefinedConstants02()  { $this->generic_test('Constants_UndefinedConstants.02'); }
    public function testConstants_UndefinedConstants03()  { $this->generic_test('Constants_UndefinedConstants.03'); }
    public function testConstants_UndefinedConstants04()  { $this->generic_test('Constants_UndefinedConstants.04'); }
}
?>