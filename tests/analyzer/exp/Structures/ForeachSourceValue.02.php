<?php

$expected     = array('foreach($d as $x => $d) { /**/ } ',
                      'foreach($d as $d => $x) { /**/ } ',
                      'foreach($d as $d) { /**/ } ',
                     );

$expected_not = array('foreach($fields as $field) { /**/ }',
                      'foreach($terms as $term_id) { /**/ }',
                      'foreach($c as $comment) { /**/ } ',
                      'foreach($data as $_order) { /**/ }',
                      'foreach($fields as $x => $field) { /**/ }',
                      'foreach($terms as $x => $term_id) { /**/ }',
                      'foreach($c as $x => $comment) { /**/ } ',
                      'foreach($data as $x => $_order) { /**/ }',
                     );

?>