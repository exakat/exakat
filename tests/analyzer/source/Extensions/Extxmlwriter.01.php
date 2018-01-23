<?php
$xw = xmlwriter_open_uri('./a.xml');
xmlwriter_set_indent($xw, 1);
$res = xmlwriter_set_indent_string($xw, ' ');

xmlwriter_start_document($xw, '1.0', 'utf8');

// A first element
xmlwriter_start_element($xw, 'tag1');

// Attribute 'att1' for element 'tag1'
xmlwriter_start_attribute($xw, 'att1');
xmlwriter_text($xw, 'valueofatt1');
xmlwriter_end_attribute($xw);

xmlwriter_write_comment($xw, 'this is a comment.');

// Start a child element
xmlwriter_start_element($xw, 'tag11');
xmlwriter_text($xw, utf8_encode('This is a sample text, Ã¤'));
xmlwriter_end_element($xw); // tag11

xmlwriter_end_element($xw); // tag1

// partial example

// A method. No ext/simplexml
$a::xmlwriter_set_indent($off, 0);

?>