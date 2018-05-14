<?php

$expected     = array('CommonMark\\Node\\Document',
                      'CommonMark\\Node\\Text',
                      'CommonMark\\Node\\Paragraph',
                      'CommonMark\\Render\\HTML($document)',
                     );

$expected_not = array('HTML( )',
                      '',
                     );

?>