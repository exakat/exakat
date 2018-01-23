<?php

$expected     = array('xmlwriter_open_uri(\'./a.xml\')',
                      'xmlwriter_set_indent($xw, 1)',
                      'xmlwriter_set_indent_string($xw, \' \')',
                      'xmlwriter_start_document($xw, \'1.0\', \'utf8\')',
                      'xmlwriter_start_element($xw, \'tag1\')',
                      'xmlwriter_start_attribute($xw, \'att1\')',
                      'xmlwriter_text($xw, \'valueofatt1\')',
                      'xmlwriter_end_attribute($xw)',
                      'xmlwriter_write_comment($xw, \'this is a comment.\')',
                      'xmlwriter_start_element($xw, \'tag11\')',
                      'xmlwriter_text($xw, utf8_encode(\'This is a sample text, Ã¤\'))',
                      'xmlwriter_end_element($xw)',
                      'xmlwriter_end_element($xw)',
                     );

$expected_not = array('xmlwriter_set_indent($off, 0)',
                     );

?>