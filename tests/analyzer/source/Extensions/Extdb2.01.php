<?php
$conn = db2_connect($database, $user, $password);

if ($conn) {
    $stmt = db2_exec($conn, 'SELECT count(*) FROM animals');
    $res = db2_fetch_array( $stmt );
    echo $res[0] . PHP_EOL;
    
    // Turn AUTOCOMMIT off
    db2_autocommit($conn, DB2_AUTOCOMMIT_OFF);
    echo DB2_OTHER_CONSTANT;
   
    // Delete all rows from ANIMALS
    db2_exec($conn, 'DELETE FROM animals');
    
    $stmt = db2_exec($conn, 'SELECT count(*) FROM animals');
    $res = db2_fetch_array( $stmt );
    echo $res[0] . PHP_EOL;
    
    // Roll back the DELETE statement
    db2_rollback( $conn );
    
    $stmt = db2_exec( $conn, 'SELECT count(*) FROM animals' );
    $res = db2_fetch_array( $stmt );
    db2_fetch_something($stmt); // such variable doesn't exists.
    echo $res[0] . PHP_EOL;
    db2_close($conn);
}
?>