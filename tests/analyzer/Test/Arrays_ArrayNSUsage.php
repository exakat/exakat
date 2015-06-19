<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Arrays_ArrayNSUsage extends Analyzer {
    /* 2 methods */

    public function testArrays_ArrayNSUsage01()  { $this->generic_test('Arrays_ArrayNSUsage.01'); }
    public function testArrays_ArrayNSUsage02()  { $this->generic_test('Arrays_ArrayNSUsage.02'); }
}
?>