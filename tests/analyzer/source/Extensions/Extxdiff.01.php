<?php
$old_version = 'my_script.php';
$new_version = 'my_new_script.php';

xdiff_file_diff($old_version, $new_version, 'my_script.diff', 2);

xdiff_file_difference($old_version, $new_version, 'my_script.diff', 2);

?>