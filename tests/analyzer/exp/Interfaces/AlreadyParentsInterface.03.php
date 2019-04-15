<?php

$expected     = array('class b extends a implements i { /**/ } ',
                      'class d extends c implements i { /**/ } ',
                     );

$expected_not = array('class a extends i { /**/ } ',
                      'class e extends d { /**/ } ',
                      'class c extends b implements j { /**/ } ',
                     );

?>