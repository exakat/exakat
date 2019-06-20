<?php

use function get_html_translation_table as get_html_translation_table2;
use const HTML_ENTITIES as HTML_ENTITIES2;
use const T_COMMENT as T_COMMENT2;
use const ENT_COMPAT as ENT_COMPAT2;


get_html_translation_table2(HTML_ENTITIES2, ENT_COMPAT2);

get_html_translation_table2(1);
get_html_translation_table2(T_COMMENT2);
