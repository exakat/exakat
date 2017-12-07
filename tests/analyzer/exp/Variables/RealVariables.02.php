<?php

$expected     = array('Stdclass $yyy',
                      '$y',
                      '$yy = 2',
                      'Stdclass $y = null',
                      '$global',
                      'Stdclass $yt = null',
                      '$ty',
                      '$xy',
                      '$x',
                      '$staticxywd',
                      '$y',
                      '$yyt = 2',
                      'Stdclass $yyyt',
                      '$t',
                      '$staticxy',
                      '$statictfy',
                      '$statictfywd',
                     );

$expected_not = array('$t',
                      '$t2',
                      '$tWithDefault',
                      '$xWithDefault',
                      '$x2',
                      '$x',
                     );

?>