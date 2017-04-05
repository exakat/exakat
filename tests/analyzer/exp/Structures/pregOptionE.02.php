<?php

$expected     = array('preg_replace(\'~AAAC~e\', \'B\', $str)',
                      '\preg_replace(\'~AAAB~ie\', \'B\', $str)');

$expected_not = array('preg_replace(\'~AAAA~ei\', \'B\', $str)');

?>