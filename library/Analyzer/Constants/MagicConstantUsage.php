<?php

namespace Analyzer\Constants;

use Analyzer;

class MagicConstantUsage extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Magicconstant');
    }
}

?>
