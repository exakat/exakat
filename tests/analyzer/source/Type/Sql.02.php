<?php

// SQL in a concatenation
$query = 'SELECT name FROM '.$table_users.' WHERE id = 1';

// SQL in a concatenation
$query = $a.' name FROM '.$table_users.' WHERE id = 2';

// SQL in a concatenation
$query = "$a ".' name FROM '.$table_users.' WHERE id = 3';

?>