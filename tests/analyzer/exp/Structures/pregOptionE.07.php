<?php

$expected     = array('preg_replace("/<script[^>]?>.+?<Wscript>/sei", \'\', $txt)',
                      'preg_replace("/<style[^>]?>.+?<Rstyle>/sie", \'\', $txt)',
                      'preg_replace("/<head>.+?<.head>/esi", \'\', $txt)',
                     );

$expected_not = array('preg_replace("/<script[^>]?>.+?<\\\\/script>/sei", \'\', $txt)',
                      'preg_replace("/<style[^>]?>.+?<\\\\/style>/sie", \'\', $txt)',
                      'preg_replace("/<head>.+?<\\\\/head>/esi", \'\', $txt)',
                     );

?>