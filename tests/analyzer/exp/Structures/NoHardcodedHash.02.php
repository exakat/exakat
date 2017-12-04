<?php

$expected     = array('\'$2a$07$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi\'',
                      '\'$6$rounds=5000$usesomesillystri$D4IrlXatmP7rx3P3InaxBeoomnAihCKRVQP22JZ6EY47Wc6BkroIuUUBOov1i.S5KPgErtP/EN5mcO.ChWQW21\'',
                      '\'$1$rasmusle$rISCgZzpwk3UhDidwXvin0\'',
                      '\'$5$rounds=5000$usesomesillystri$KqJWpanXZHKq2BOB43TSaYhEWsQ1Lr5QNyPCDH/Tp.6\'',
                     );

$expected_not = array('\'$3$rasmusle$rISCgZzpwk3UhDidwXvin0\'',
                      '"\\$1\\$asdlfjasdf"',
                     );

?>