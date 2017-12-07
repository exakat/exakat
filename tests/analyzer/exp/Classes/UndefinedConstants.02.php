<?php

$expected     = array('x4::c5',
                      'x52::ic3',
                      'x51::ic2',
                      'x51::ic3',
                      'self::c5',
                      'static::c5',
                      'parent::c5',
                      'parent::c4',
                     );

$expected_not = array('x4::c4',
                      'x4::c3',
                      'x4::c2',
                      'x4::c1',
                      'x53::ic1',
                      'x53::ic2',
                      'x53::ic3',
                      'x52::ic1',
                      'x52::ic2',
                      'x51::ic1',
                      'parent::c3',
                      'parent::c2',
                      'parent::c1',
                      'static::c4',
                      'static::c3',
                      'static::c2',
                      'static::c1',
                      'self::c4',
                      'self::c3',
                      'self::c2',
                      'self::c1',
                     );

?>