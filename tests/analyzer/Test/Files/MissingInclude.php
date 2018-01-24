<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Files_MissingInclude extends Analyzer {
    /* 2 methods */

    public function testFiles_MissingInclude01()  { $this->generic_test('Files/MissingInclude.01'); }
    public function testFiles_MissingInclude02()  { $this->generic_test('Files/MissingInclude.02'); }
}
?>