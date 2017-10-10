<?php

$expected     = array( 'move_uploaded_file($_FILES[\'upload1\'][\'tmp_name\'], "unsafe/" . $id . ".$extension")',
                     );

$expected_not = array( 'move_uploaded_file($_FILES[\'upload2\'][\'tmp_name\'], "safe/" . $id . \'.some_extension\')'
                     );

?>