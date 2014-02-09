<?php

namespace Analyzer\Themes;

class Php54 extends \Analyzer\Analyzer {

	public function dependsOn() {
        $dependencies = array(
            'Break0',
            'BreakNonInteger',
        );
        return $dependencies;
	}
}

?>