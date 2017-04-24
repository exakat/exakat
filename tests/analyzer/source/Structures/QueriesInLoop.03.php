<?php

    $conn = sqlsrv_connect( $serverName, $connectionInfo);

    $sql = "SELECT country FROM cities WHERE name = ?";

    $stmt = sqlsrv_prepare( $conn, $sql, array( &$name ));

    $cities = array( 'Paris', 'Amsterdam', 'Beijing');

    // Execute the statement for each order.
    foreach( $cities as $name) {
        if( sqlsrv_execute( $stmt ) === false ) {
              print "Error with $name in the database\n";
              break 1;
        } 
        
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)){
          print $name." is in ".$row['country'].".\n"; 
        }
    }

?>