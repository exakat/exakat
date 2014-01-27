<?php

namespace Analyzer\Interfaces;

use Analyzer;

class Php extends Analyzer\Analyzer {

    public function dependsOn() {
        return array("Analyzer\\Interfaces\\InterfaceUsage");
    }
    
    public function analyze() {
        $ini = parse_ini_file(dirname(dirname(dirname(__DIR__))).'/data/php_interfaces.ini');

        $this->analyzerIs("Analyzer\\Interfaces\\InterfaceUsage")
             ->code($ini['interfaces']);
    }
}

?>