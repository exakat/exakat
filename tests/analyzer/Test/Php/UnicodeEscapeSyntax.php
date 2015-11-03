<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_UnicodeEscapeSyntax extends Analyzer {
    /* 1 methods */

    public function testPhp_UnicodeEscapeSyntax01()  { $this->generic_test('Php_UnicodeEscapeSyntax.01'); }
}
?>