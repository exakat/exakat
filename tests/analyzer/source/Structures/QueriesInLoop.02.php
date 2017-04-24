<?php

if ($stmt = mysqli_prepare($link, "SELECT A FROM B WHERE C=?")) {
    foreach ($bArray as $b) {

        mysqli_stmt_bind_param($stmt, "s", $b);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $c);
        mysqli_stmt_fetch($stmt);

        printf("%s is in district %s\n", $a, $c);
        mysqli_stmt_close($stmt);
    }
    
    // Closing en masse is good
    foreach ($stmtArray as $stmt) {
        mysqli_stmt_close($stmt);
    }
    
}

?>