<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ReflectionExportIsDeprecated extends Analyzer {
    /* 1 methods */

    public function testPhp_ReflectionExportIsDeprecated01()  { $this->generic_test('Php/ReflectionExportIsDeprecated.01'); }
}
?>