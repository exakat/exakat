<?php

$expected     = array('!empty($_SERVER[\'same case\']) ? $_SERVER[\'HTTP_PROXY\'] : $_SERVER[\'HTTP_PROXY\']',
                     );

$expected_not = array('!empty($_SERVER[\'same case\']) ? $_SERVER[\'http_proxy\'] : $_SERVER[\'HTTP_PROXY\']',
                     );

?>