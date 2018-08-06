<?php

$expected     = array('return ($num = round($difference / (3600))) . \' \' . ngettext(\'hour\', \'hours\', $num)',
                      'return $id++',
                     );

$expected_not = array('$a->b++',
                      '$this->_values[$key][$extra] = $value',
                      'function ( ) use ($name, $method) { /**/ } ',
                      'preg_replace_callback(\'(<span[^>]*style="color:\\s*(?P<color>#[A-Fa-f0-9]{3,6})"[^>]*>)\', function ($matches) use ($styleMapping) { /**/ } , $content)',
                      '[\'fname\' => $faker->firstName, \'lname\' => $faker->lastName, \'email\' => $faker->unique( )->safeEmail,]',
                     );

?>