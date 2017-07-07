<?php

// By default, this dir is 777
mkdir('/path/to/dir');

// Explicitely, this is wanted. It may also be audited easily
mkdir('/path/to/dir2', 0777);

// This dir is limited to the current user. 
mkdir('/path/to/dir3', 0700);

?>