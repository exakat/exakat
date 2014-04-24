<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Type_Md5String extends Analyzer {
    /* 2 methods */

    public function testType_Md5String01()  { $this->generic_test('Type_Md5String.01'); }
    public function testType_Md5String02()  { $this->generic_test('Type_Md5String.02'); }
}
?>