<?php

namespace Analyzer\Themes;

class Test extends \Analyzer\Analyzer {

	public function dependsOn() {
        $dependencies = array(
            'Binary',
            'Hexadecimal',
            'Integer',
        );
        return $dependencies;
	}
}

?>