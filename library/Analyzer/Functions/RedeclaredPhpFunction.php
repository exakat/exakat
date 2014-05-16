<?php

namespace Analyzer\Functions;

use Analyzer;

class RedeclaredPhpFunction extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Functions\\Functionnames");
    }
    
    public function analyze() {
        $exts = $this->loadIni('php_distribution_53.ini');
        
        $extensions = array();
        foreach($exts['ext'] as $ext) {
            if ($ext2 = $this->loadIni($ext.'.ini')) {
                $extensions = array_merge($extensions, $ext2['functions']);
            }
        }
        
        $this->atomIs("Function")
             ->outIs('NAME')
             ->analyzerIs("Analyzer\\Functions\\Functionnames")
             ->code($extensions, true);
    }
}

?>