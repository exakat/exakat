<?php

namespace Test\Wordpress;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UnescapedVariables extends Analyzer {
    /* 3 methods */

    public function testWordpress_UnescapedVariables01()  { $this->generic_test('Wordpress/UnescapedVariables.01'); }
    public function testWordpress_UnescapedVariables02()  { $this->generic_test('Wordpress/UnescapedVariables.02'); }
    public function testWordpress_UnescapedVariables03()  { $this->generic_test('Wordpress/UnescapedVariables.03'); }
}
?>