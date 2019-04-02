<?php

$expected     = array('Swoole\\Server::finish(1, 2, 3, 4, 5)',
                      'Swoole\\Server::finish(1, 2, 3, 4)',
                      'Swoole\\Server::finish(1, 2, 3)',
                      'Swoole\\Server::finish(1, 2)',
                      'Swoole\\Server::finish( )',
                     );

$expected_not = array('Swoole\\Server::finish(1 )',
                     );

?>