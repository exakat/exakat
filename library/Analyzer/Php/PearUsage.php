<?php

namespace Analyzer\Php;

use Analyzer;

class PearUsage extends Analyzer\Common\ClassUsage {
    public function analyze() {
        $this->classes = $this->loadIni('pear.ini', 'classes');

        parent::analyze();
    }
}

?>
