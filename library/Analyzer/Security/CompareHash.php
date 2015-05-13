<?php

namespace Analyzer\Security;

use Analyzer;

class CompareHash extends Analyzer\Analyzer {
    public function analyze() {
        // md5() == something
        $this->atomIs('Comparison')
             ->code(array('==', '!='))
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Functioncall')
             ->code(array('hash', 'md5', 'sha1', 'md5_file', 'sha1_file', 'crc32','crypt'))
             ->back('first');
        $this->prepareQuery();

        // if (hash()) 
        $this->atomIs('Ifthen')
             ->outIs('CONDITION')
             ->atomIs('Functioncall')
             ->code(array('hash', 'md5', 'sha1', 'md5_file', 'sha1_file', 'crc32','crypt'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
