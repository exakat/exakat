<?php

namespace Analyzer\Themes;

class Security extends \Analyzer\Analyzer {

	public function dependsOn() {
        $dependencies = array(
            'EvalUsage',
            'VardumpUsage',
            'PhpinfoUsage',
        );
        return $dependencies;
	}
}

?>