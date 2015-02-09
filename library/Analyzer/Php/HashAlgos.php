<?php

namespace Analyzer\Php;

use Analyzer;

class HashAlgos extends Analyzer\Analyzer {
    public static $functions = array('hash', 'hash_algo', 'hash_hmac_file', 'hash_hmac', 'hash_init', 'hash_pbkdf2');
    
    public function analyze() {
        $algos = $this->loadIni('hash_algos.ini', 'algos');
        
        $this->atomFunctionIs(self::$functions)
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String')
             ->noDelimiterIsNot($algos);
        $this->prepareQuery();
    }
}

?>
