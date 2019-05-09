<?php
echo uuid_type("b691c99c-7fc5-11d8-9fa8-00065b896488") == UUID_TYPE_TIME   ? "OK\n" : "Failure\n";
echo uuid_type("878b258c-a9f1-467c-8e1d-47d79ca2c01b") == UUID_TYPE_RANDOM ? "OK\n" : "Failure\n";
echo uuid_type("00000000-0000-0000-0000-000000000000") == UUID_TYPE_NULL   ? "OK\n" : "Failure\n";


// Not a uuid function
uuid_return( );

?>