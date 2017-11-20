<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_Argon2Usage extends Analyzer {
    /* 1 methods */

    public function testPhp_Argon2Usage01()  { $this->generic_test('Php/Argon2Usage.01'); }
}
?>