<?php

$expected     = array('if(!@$_GET[\'w\'] && !@$_GET[\'wp\'] && !@$_GET[\'wl\'] && !@$_GET[\'ws\'] && !@$_GET[\'h\'] && !@$_GET[\'hp\'] && !@$_GET[\'hl\'] && !@$_GET[\'hs\']) { /**/ } else { /**/ } ',
                      'elseif(!@$_GET[\'w\'] && !@$_GET[\'wp\'] && !@$_GET[\'wl\'] && !@$_GET[\'ws\'] && !@$_GET[\'h\'] && !@$_GET[\'hp\'] && !@$_GET[\'hl\'] && !@$_GET[\'hs\']) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if ($a) { /**/ } ',
                      'if(!@$_POST[\'w\'] && !@$_GET[\'wp\'] && $a) { /**/ } else { /**/ } ',
                     );

?>