<?php
	pcntl_waitpid($pid, $status = 0);
	parse_str($_SERVER['QUERY_STRING'], $_GET);

	pcntl_waitpid($pid, $status);
	array_pop(array_slice([1,2,3], 0, 1));

?>