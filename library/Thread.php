<?php

class Thread {
    private $pipes = array();
    private $process = array();
    function __construct() {
    
    }
    
    public function run($command) {
        $descriptors = array( 0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
                              1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
                              2 => array('file', "/tmp/error-output.txt", 'a') // stderr is a file to write to
                            );
        $this->process[] = proc_open($command.' &', $descriptors, $pipes);
        // only keeping the read pipe
        $this->pipes[] = $pipes[0];
    }
    
    public function areAllFinished() {
	    $w = null;
    	$e = null;
    	$pipes = $this->pipes;
    	$n = stream_select($pipes, $w, $e, 0);
    	
    	if ($n > 0) {
		    foreach($pipes as $id => $pipe) {
		        $status = proc_get_status($this->process[$id]);
		        if ($status['running'] === false) {
		            unset($this->process[$id]);
		            unset($this->pipes[$id]);
		        }
		    }
    	}
    	
    	return count($this->process);
    }

    public function waitForAll() {
        if (!$this->areAllFinished()) {
            while($this->areAllFinished()) {
                sleep(rand(0.5, 1,5));
            }
        }
        
        return true;
    }
}

?>
