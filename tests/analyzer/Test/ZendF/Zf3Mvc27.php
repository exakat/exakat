<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class ZendF_Zf3Mvc27 extends Analyzer {
    /* 1 methods */

    public function testZendF_Zf3Mvc2701()  { $this->generic_test('ZendF/Zf3Mvc27.01'); }
}
?>