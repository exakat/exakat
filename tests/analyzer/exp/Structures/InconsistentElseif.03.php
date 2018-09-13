<?php

$expected     = array('if(!isset($a6->config[\'program\'])) { /**/ } elseif(is_string($a7->config[\'program\'])) { /**/ } else { /**/ } ',
                      'if(!isset($a7->config[\'program\'])) { /**/ } elseif(is_string($a8->config[\'program\'])) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if(!isset($a1->config[\'program\'])) { /**/ } elseif(is_string($a1->nope)) { /**/ } else { /**/ } ',
                      'if(!isset($a2->config[\'program\'])) { /**/ } elseif(is_string($a2->nope)) { /**/ } else { /**/ } ',
                      'if(!isset($a3->config[\'program\'])) { /**/ } elseif(is_string($a3->nope)) { /**/ } else { /**/ } ',
                      'if(!isset($a4->config[\'program\'])) { /**/ } elseif(is_string($a4->nope[\'program\'])) { /**/ } else { /**/ } ',
                     );

?>