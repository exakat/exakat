<?php

$expected     = array('"http://{$_SERVER[\'HTTP_HOST\']}:{$_SERVER[\'HTTP_PORT\']}"',
                      '"http://" . $_SERVER[\'HTTP_HOST\'] . ":" . $_SERVER[\'HTTP_PORT\'] . SOME_URL',
                      '"http://" . $_SERVER[\'SERVER_NAME\'] . ":" . $_SERVER[\'SERVER_PORT\'] . SOME_URL',
                      '"http://{$_SERVER[\'SERVER_NAME\']}:{$_SERVER[\'SERVER_PORT\']}"',
                     );

$expected_not = array('"http://{$_SERVER[\'SERVER_HOST\']}:{$_SERVER[\'SERVER_PORT\']}"',
                      '"http://" . $_SERVER[\'SERVER_HOST\'] . ":" . $_SERVER[\'SERVER_PORT\'] . SOME_URL',
                     );

?>