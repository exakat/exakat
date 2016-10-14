<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class ZendF_UndefinedClass112 extends Analyzer {
    /* 1 methods */

    public function testZendF_UndefinedClass11201()  { $this->generic_test('ZendF/UndefinedClass112.01'); }
}
?>