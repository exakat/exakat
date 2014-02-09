<?php

namespace Analyzer\Themes;

class Performance extends \Analyzer\Analyzer {

	public function dependsOn() {
        $dependencies = array(
            'EvalUsage',
            'ForWithFunctioncall',
            'ForeachSourceNotVariable',
            'OnceUsage',
        );
        return $dependencies;
	}
}

?>