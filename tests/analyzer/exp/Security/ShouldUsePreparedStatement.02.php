<?php

$expected     = array('$object2->query(\'select \' . $a . \' from table \')',
                      '$object->query("select $a from table ")',
                     );

$expected_not = array('$object2->notQuery($res, \'select \'.$a.\' from table \')',
                      '$wrongPosition->query(2, \'select \'.$a.\' from table \')',
                     );

?>