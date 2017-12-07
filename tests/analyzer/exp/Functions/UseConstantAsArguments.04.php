<?php

$expected     = array('get_html_translation_table(HTML_ENTITIES, T_COMMENT)',
                      'get_html_translation_table(HTML_ENTITIES, \\T_COMMENT)',
                      'get_html_translation_table(HTML_ENTITIES, HTML_ENTITIES2)',
                      'get_html_translation_table(HTML_ENTITIES, \\HTML_ENTITIES2)',
                     );

$expected_not = array('get_html_translation_table(HTML_ENTITIES, ENT_COMPAT)',
                      'get_html_translation_table(HTML_ENTITIES, \\ENT_COMPAT)',
                     );

?>