<?php

namespace Analyzer\Php;

use Analyzer;

class DirectivesUsage extends Analyzer\Common\UsedDirective {
    public function analyze() {
        $this->directives = $this->loadIni('php_directives.ini', 'directives');

        parent::analyze();
    }
}

?>
