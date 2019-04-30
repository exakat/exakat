<?php

$expected     = array('sqliTE4(\':memory:\')', ,
                      'a\sqliTE3(\':memory:\')',
                     );

$expected_not = array('\Sqlite3(\':memory:\')',
                      'sqliTE3(\':memory:\')',
                     );

?>