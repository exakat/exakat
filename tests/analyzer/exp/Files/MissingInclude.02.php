<?php

$expected     = array('require __DIR__ . \'/c/b.php\')',
                      'include_once __DIR__ . \'/c/d/b.php\'',
                      'require_once __DIR__ . \'/c/d/e/b.php\'',
                      'include __DIR__ . \'/b.php\'',
                     );

$expected_not = array('require __DIR__ . \'/c/a.php\'',
                      'include_once __DIR__ . \'/c/d/a.php\'',
                      'require_once __DIR__ . \'/c/d/e/a.php\'',
                      'include __DIR__ . \'/a.php\'',
                     );

?>