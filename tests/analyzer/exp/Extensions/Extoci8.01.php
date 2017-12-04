<?php

$expected     = array('oci_connect(\'hr\', \'welcome\', \'localhost/XE\')',
                      'oci_error( )',
                      'oci_parse($conn, \'SELECT * FROM departments\')',
                      'oci_error($conn)',
                      'oci_execute($stid)',
                      'oci_error($stid)',
                      'oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)',
                      'oci_free_statement($stid)',
                      'oci_close($conn)',
                      'OCI_RETURN_NULLS',
                      'OCI_ASSOC',
                     );

$expected_not = array(
                     );

?>