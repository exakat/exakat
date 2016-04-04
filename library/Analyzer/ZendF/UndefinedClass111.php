<?php

namespace Analyzer\ZendF;

use Analyzer;

class UndefinedClass111 extends UndefinedClasses {
    public function analyze() {
        $this->release = '1.11';
        parent::analyze();
    }
}

?>
