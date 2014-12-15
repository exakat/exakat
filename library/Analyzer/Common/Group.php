<?php

namespace Analyzer\Common;

class Group extends \Analyzer\Analyzer {

	public function dependsOn() {
	    $glob = dirname(__DIR__)."/".basename(str_replace('\\', '/', get_class($this)))."/*";
	    
        $dependencies = glob($glob);
        foreach($dependencies as $id => $d) {
            $d = str_replace(dirname(dirname(__DIR__))."/",'', $d);
            $d = str_replace('.php','', $d);
            $d = str_replace('/','\\', $d);
            $dependencies[$id] = $d;
        }

        return $dependencies;
	}
}

?>
