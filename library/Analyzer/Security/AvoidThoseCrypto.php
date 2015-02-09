<?php

namespace Analyzer\Security;

use Analyzer;

class AvoidThoseCrypto extends Analyzer\Analyzer {
    public function analyze() {
        // in hashing functions
        $this->atomFunctionIs(Analyzer\Php\HashAlgos::$functions)
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->noDelimiter(array('md2', 'md4', 'md5', 'crc32', 'crc32b', 'sha0', 'sha1'));
        $this->prepareQuery();

        // in hashing functions
        $this->atomFunctionIs(array('crypt', 'md5', 'md5_file', 'sha1_file', 'sha1', 'crc32'));
        $this->prepareQuery();
    }
}

?>
