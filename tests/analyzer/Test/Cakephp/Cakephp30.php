<?php

namespace Test\Cakephp;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Cakephp30 extends Analyzer {
    /* 1 methods */

    public function testCakephp_Cakephp3001()  { $this->generic_test('Cakephp/Cakephp30.01'); }
}
?>