<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class LetterCharsLogicalFavorite extends Analyzer {
    /* 3 methods */

    public function testPhp_LetterCharsLogicalFavorite01()  { $this->generic_test('Php/LetterCharsLogicalFavorite.01'); }
    public function testPhp_LetterCharsLogicalFavorite02()  { $this->generic_test('Php/LetterCharsLogicalFavorite.02'); }
    public function testPhp_LetterCharsLogicalFavorite03()  { $this->generic_test('Php/LetterCharsLogicalFavorite.03'); }
}
?>