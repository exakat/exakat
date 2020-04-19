<?php

$expected     = Array(
    array
        (
            "including" => "b.php",
            "included" => "/a.php",
        ),

    array
        (
            "including" => "c.php",
            "included" => "/a.php",
        ),

    array
        (
            "including" => "e.php",
            "included" => "/a.php",
        ),

    array
        (
            "including" => "d.php",
            "included" => "/a.php",
        ),

    array
        (
            "including" => "non_existent.php",
            "included" => "/a.php",
        ),

);

$expected_not = array(array
        (
            "including" => "require_once 'non_existent.php'",
            "included" => "/a.php",
        ),
                     );

?>