<?php

namespace Analyzer\Functions;

use Analyzer;

class RedeclaredPhpFunction extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Functions\\Functionnames');
    }
    
    public function analyze() {
        $extensions = $this->loadIni('php_distribution_53.ini');
        
        $extensionFunctions = array();
        foreach($extensions['ext'] as $ext) {
            if ($iniFile = $this->loadIni($ext.'.ini')) {
                $extensionFunctions = array_merge($extensionFunctions, $iniFile['functions']);
            }
        }
        
        $this->atomIs('Function')
             ->outIs('NAME')
             ->analyzerIs('Analyzer\\Functions\\Functionnames')
             ->code($extensionFunctions, true);
    }
}

?>
