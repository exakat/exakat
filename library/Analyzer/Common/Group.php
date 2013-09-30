<?php

namespace Analyzer\Common;

class Group extends \Analyzer\Analyzer {

	protected function dependsOn() {
	    // @doc get the classes in the folder with the same name as the current class. 
	    $name = get_class($this);
	    
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