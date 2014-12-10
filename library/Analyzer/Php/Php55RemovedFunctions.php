<?php

namespace Analyzer\Php;

use Analyzer;

class Php55RemovedFunctions extends Analyzer\Common\FunctionUsage {
    protected $phpVersion = "5.5-";
    
    public function analyze() {
        $this->functions = array('php_logo_guid', 
                                 'php_egg_logo_guid',
                                 'php_real_logo_guid',
                                 'zend_logo_guid',
                                 'mcrypt_cbc',
                                 'mcrypt_cfb',
                                 'mcrypt_ecb',
                                 'mcrypt_ofb');
        parent::analyze();
    }
}

?>