<?php

$expected     = array('$shell->error( )',
                      '$resultset->_calculateTypeMap( )',
                     );

$expected_not = array('Cake\\ORM\\ResultSet::_calculateTypeMap()',
                      'Shell::error()',
                     );

?>