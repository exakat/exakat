<?php

$expected     = array('preg_replace(\'/(a)(\' . $kw . \')(b)/sie\', \'c\' . $d . \'e\', $data)',
                     );

$expected_not = array('preg_replace(\'/(a)(\' . $kw . \')(b)/si\', \'c\' . $d . \'e\', $data)',
                     );

?>