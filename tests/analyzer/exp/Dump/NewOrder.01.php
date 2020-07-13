<?php

$expected     = array(
                        array(
                            "calling" => "\a",
                            "called" => "\b",
                        ),
                
                        array(
                            "calling" => "\b",
                            "called" => "\c",
                        ),
                
                        array(
                            "calling" => "\b",
                            "called" => "\c2",
                        ),
                     );

$expected_not = array(array(
                        ),
                     );

?>