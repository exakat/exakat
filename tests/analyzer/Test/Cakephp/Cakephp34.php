<?php

namespace Test\Cakephp;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Cakephp34 extends Analyzer {
    /* 1 methods */

    public function testCakephp_Cakephp3401()  { $this->generic_test('Cakephp/Cakephp34.01'); }
}
?>