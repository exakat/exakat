<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Files_NotDefinitionsOnly extends Analyzer {
    /* 6 methods */

    public function testFiles_NotDefinitionsOnly01()  { $this->generic_test('Files_NotDefinitionsOnly.01'); }
    public function testFiles_NotDefinitionsOnly02()  { $this->generic_test('Files_NotDefinitionsOnly.02'); }
    public function testFiles_NotDefinitionsOnly03()  { $this->generic_test('Files_NotDefinitionsOnly.03'); }
    public function testFiles_NotDefinitionsOnly04()  { $this->generic_test('Files_NotDefinitionsOnly.04'); }
    public function testFiles_NotDefinitionsOnly05()  { $this->generic_test('Files_NotDefinitionsOnly.05'); }
    public function testFiles_NotDefinitionsOnly06()  { $this->generic_test('Files_NotDefinitionsOnly.06'); }
}
?>