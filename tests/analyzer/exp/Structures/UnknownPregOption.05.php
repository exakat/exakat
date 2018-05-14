<?php

$expected     = array('preg_replace(\'/(a)(\' . $kw . \')(b)/siK\', \'c\' . $d . \'e\', $data)',
                     );

$expected_not = array('preg_replace(\'/(a)(\' . $kw . \')(b)/si\', \'c\' . $d . \'e\', $data)',
                      'preg_replace("\\"(a)\\"sieK", \'b\', $c)',
                      'preg_replace(\'\\"(a)\\"sieK\', \'b\', $c)',
                      'preg_replace(\'\\"(a)\\"sie\', \'b\', $c)',
                     );

?>