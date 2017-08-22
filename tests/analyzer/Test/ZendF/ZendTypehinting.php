<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class ZendF_ZendTypehinting extends Analyzer {
    /* 2 methods */

    public function testZendF_ZendTypehinting01()  { $this->generic_test('ZendF/ZendTypehinting.01'); }
    public function testZendF_ZendTypehinting02()  { $this->generic_test('ZendF/ZendTypehinting.02'); }
}
?>