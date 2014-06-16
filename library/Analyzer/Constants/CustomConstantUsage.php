<?php

namespace Analyzer\Constants;

use Analyzer;

class CustomConstantUsage extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Constants\\ConstantUsage');
    }
    
    public function analyze() {
        // @todo need generalisation here! 
        $curl_constants = $this->loadConstants('curl.ini');
        $libxml_constants = $this->loadConstants('libxml.ini');
        $standard_constants = $this->loadConstants('standard.ini');
        $php_constants = $this->loadConstants('php_constants.ini');
        $json_constants = $this->loadConstants('json.ini');
        $pcntl_constants = $this->loadConstants('pcntl.ini');

        $this->atomIs("Identifier")
             ->analyzerIs('Analyzer\\Constants\\ConstantUsage')
             ->codeIsNot($curl_constants)
             ->codeIsNot($libxml_constants)
             ->codeIsNot($standard_constants)
             ->codeIsNot($json_constants)
             ->codeIsNot($pcntl_constants)
             ->codeIsNot($php_constants);
        $this->prepareQuery();

        // @note NSnamed are OK by default (mmm, no!)
        $this->atomIs("Nsname")
             ->analyzerIs('Analyzer\\Constants\\ConstantUsage');
        $this->prepareQuery();
    }
    
    public function loadConstants($source) {
        if (substr($source, -4) == '.ini') {
            $ini = $this->loadIni($source);
            extract($ini);
            
            if (count($constants) == 1 && empty($constants[0])) {
                $constants = array();
            }
        }
        
        return $constants;
    }
}

?>