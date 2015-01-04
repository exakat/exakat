<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Files_GlobalCodeOnly extends Analyzer {
    /* 1 methods */

    public function testFiles_GlobalCodeOnly01()  { $this->generic_test('Files_GlobalCodeOnly.01'); }
}
?>