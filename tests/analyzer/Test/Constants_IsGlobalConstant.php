<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Constants_IsGlobalConstant extends Analyzer {
    /* 4 methods */

    public function testConstants_IsGlobalConstant01()  { $this->generic_test('Constants_IsGlobalConstant.01'); }
    public function testConstants_IsGlobalConstant02()  { $this->generic_test('Constants_IsGlobalConstant.02'); }
    public function testConstants_IsGlobalConstant03()  { $this->generic_test('Constants_IsGlobalConstant.03'); }
    public function testConstants_IsGlobalConstant04()  { $this->generic_test('Constants_IsGlobalConstant.04'); }
}
?>