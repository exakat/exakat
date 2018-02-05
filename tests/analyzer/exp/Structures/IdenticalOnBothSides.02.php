<?php

$expected     = array('(strpos($this->_format, \'Y\') !== false) or (strpos($this->_format, \'Y\') !== false)',
                     );

$expected_not = array('(strpos($this->_format, \'Y\') !== false) or (strpos($this->_format, \'y\') !== false)',
                      '(isset($headers[\'X-Requested-With\']) && ($headers[\'X-Requested-With\'] == \'XMLHttpRequest\')) || (isset($headers[\'x-requested-with\']) && ($headers[\'x-requested-with\'] == \'XMLHttpRequest\'))',
                     );

?>