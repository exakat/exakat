<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Constants_BadConstantnames extends Analyzer {
    /* 3 methods */

    public function testConstants_BadConstantnames01()  { $this->generic_test('Constants_BadConstantnames.01'); }
    public function testConstants_BadConstantnames02()  { $this->generic_test('Constants_BadConstantnames.02'); }
    public function testConstants_BadConstantnames03()  { $this->generic_test('Constants_BadConstantnames.03'); }
}
?>