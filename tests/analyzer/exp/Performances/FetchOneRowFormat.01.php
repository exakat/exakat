<?php

$expected     = array('$res->fetchRow(SQLITE3_BOTH)',
                      '$res->fetchRow(\\SQLITE3_BOTH)',
                      '$res->fetchRow( )',
                     );

$expected_not = array('$res->fetchRow(SQLITE3_NUM)',
                      '$res->fetchRow(\\SQLITE3_NUM)',
                      '$res->fetchRow(SQLITE3_ASSOC)',
                      '$res->fetchRow(\\SQLITE3_ASSOC)',
                     );

?>