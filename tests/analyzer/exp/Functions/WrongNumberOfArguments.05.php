<?php

$expected     = array('range($a)',
                      'unserialize($GLOBALS[\'W\'][\'EXT\'][\'w\'][\'www\'], [\'www\' => false])',
                     );

$expected_not = array('range(...$a)',
                      'range($d, ...$e)',
                      'range(...$d, ...$e)',
                      'array_merge_recursive(...array_values($b))',
                      'array_multisort($sortTitle, SORT_DESC, $statuses)',
                      'file_get_contents($cc, false, $c)',
                      'imap_open($hostname, $a->x, $b->x, null, 1, array(\'D\' => \'E\'))',
                     );

?>