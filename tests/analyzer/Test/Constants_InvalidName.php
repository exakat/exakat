<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Constants_InvalidName extends Analyzer {
    /* 3 methods */

    public function testConstants_InvalidName01()  { $this->generic_test('Constants_InvalidName.01'); }
    public function testConstants_InvalidName02()  { $this->generic_test('Constants_InvalidName.02'); }
    public function testConstants_InvalidName03()  { $this->generic_test('Constants_InvalidName.03'); }
}
?>