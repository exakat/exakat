<?php

$expected     = array('$XMLWriter->startDTD( )',
                      '$XMLWriter->startDTD(1, 2, 3, 4)',
                      '$XMLWriter->startDTD(1, 2, 3, 4, 5)',
                      'XMLWriter::startDTD( )',
                      'XMLWriter::startDTD(1, 2, 3, 4)',
                      'XMLWriter::startDTD(1, 2, 3, 4, 5)',
                     );

$expected_not = array('enough::ini_set(1, 2)',
                      '$enough->ini_set(1, 2)',
                      '$other->startDTD( )',
                      'startdtd()',
                      'startdtd(1, 2)',
                      'startdtd(1, 2, 3, 4, 5)',
                      'other::startDTD( )',
                     );

?>