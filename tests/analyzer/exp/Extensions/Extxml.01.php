<?php

$expected     = array('xml_get_current_line_number($xml_parser)',
                      'xml_parser_create( )',
                      'xml_parser_free($xml_parser)',
                      'xml_get_error_code($xml_parser)',
                      'xml_set_element_handler($xml_parser, "startElement", "endElement")',
                      'xml_error_string(xml_get_error_code($xml_parser))',
                      'xml_parse($xml_parser, $data, feof($fp))',
                     );

$expected_not = array(
                     );

?>