<?php

$file = 'some_file';

if (posix_access($file, POSIX_R_OK | POSIX_W_OK | POSIX_A_OK)) {
    echo 'The file is readable and writable!';

} else {
    $error = posix_get_last_error();

    echo "Error $error: " . posix_strerror($error);
}

echo posix_get_next_error();

?>