<?php

namespace Analyzer\Constants;

use Analyzer;

class PhpConstantUsage extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Constants\\ConstantUsage");
    }
    
    public function analyze() {
        $ini = parse_ini_file(dirname(dirname(dirname(__DIR__))).'/data/php_constants.ini');

        $this->analyzerIs('Analyzer\\Constants\\ConstantUsage')
             ->code($ini['constants'], true);
    }
}

?>