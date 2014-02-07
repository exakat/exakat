<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Arrays_Phparrayindex extends Analyzer {
    /* 1 methods */

    public function testArrays_Phparrayindex01()  { $this->generic_test('Arrays_Phparrayindex.01'); }
}
?>