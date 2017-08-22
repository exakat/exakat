<?php

$fam = fam_open('myApplication');
fam_monitor_directory($fam, '/tmp');
fam_not_a_function();
fam_close($fam);

?>