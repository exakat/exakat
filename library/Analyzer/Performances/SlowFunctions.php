<?php

namespace Analyzer\Performances;

use Analyzer;

class SlowFunctions extends Analyzer\Common\FunctionUsage {
    public function analyze() {
        $this->functions = array(
'array_diff',
'array_intersect',
'array_udiff',
'array_uintersect',
'array_unique',
'uasort',
'uksort',
'usort',
'in_array',
'array_search',
'preg_replace',
'array_search',
'array_search',
'array_unshift',
'strstr',
);
        parent::analyze();
    }
}

?>
