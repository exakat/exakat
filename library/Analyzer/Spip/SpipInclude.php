<?php

namespace Analyzer\Spip;

use Analyzer;

class SpipInclude extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Include')
             ->raw('filter{ it.out("ARGUMENTS").out("ARGUMENT").filter{ (it.fullcode =~ "inc_version.php").getCount() == 0}.any()}');
        $this->prepareQuery();
    }
}

?>
