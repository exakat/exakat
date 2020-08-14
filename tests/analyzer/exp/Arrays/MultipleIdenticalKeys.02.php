<?php

$expected     = array('array(self::A => \'1\', self::B => \'2\', self::B => \'3\', self::D => \'4\', self::D => \'4\', self::D => \'4\', self::D => \'4\',  )',
                      'array(self::A => \'1\', self::B => \'2\', self::B => \'3\', self::D => \'4\', x::D => \'4\', \\x::D => \'4\',  )',
                     );

$expected_not = array('array(self::A => \'1\', self::B => \'2\', self::C => \'3\', self::D => \'4\',  )',
                     );

?>