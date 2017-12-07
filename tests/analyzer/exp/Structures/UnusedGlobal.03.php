<?php

$expected     = array('$unusedGlobal1y2',
                      '$unusedGlobal2y2',
                      '$unusedGlobal1',
                      '$unusedGlobal2',
                      '$unusedGlobaly2',
                      '$unusedGlobal',
                      '$unusedGlobalglb',
                      '$unusedGlobal2glb',
                      '$unusedGlobal1glb',
                     );

$expected_not = array('$usedGlobal1y2',
                      '$usedGlobal2y2',
                      '$usedGlobal1',
                      '$usedGlobal2',
                      '$usedGlobaly2',
                      '$usedGlobal',
                      '$usedGlobalglb',
                      '$usedGlobal2glb',
                      '$usedGlobal1glb',
                     );

?>