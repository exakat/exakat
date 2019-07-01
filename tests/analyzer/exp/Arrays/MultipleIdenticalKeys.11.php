<?php

$expected     = array('array(A::class => \'1\', B::class => \'2\', B::class => \'3\', D::class => \'4\', D::class => \'4\', D::class => \'4\', D::class => \'4\',  )',
                      'array(A::class => \'1\', B::class => \'3\', \'A\' => \'4\',  )',
                     );

$expected_not = array('array(A::class => \'1\', B::class => \'2\', C::class => \'3\', D::class => \'4\',  )',
                     );

?>