<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Constants_ConstDefinePreference extends Analyzer {
    /* 3 methods */

    public function testConstants_ConstDefinePreference01()  { $this->generic_test('Constants/ConstDefinePreference.01'); }
    public function testConstants_ConstDefinePreference02()  { $this->generic_test('Constants/ConstDefinePreference.02'); }
    public function testConstants_ConstDefinePreference03()  { $this->generic_test('Constants/ConstDefinePreference.03'); }
}
?>