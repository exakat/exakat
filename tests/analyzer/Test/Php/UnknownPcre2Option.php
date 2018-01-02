<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_UnknownPcre2Option extends Analyzer {
    /* 3 methods */

    public function testPhp_UnknownPcre2Option01()  { $this->generic_test('Php/UnknownPcre2Option.01'); }
    public function testPhp_UnknownPcre2Option02()  { $this->generic_test('Php/UnknownPcre2Option.02'); }
    public function testPhp_UnknownPcre2Option03()  { $this->generic_test('Php/UnknownPcre2Option.03'); }
}
?>