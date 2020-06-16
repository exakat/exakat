<?php

preg_replace(array('/A/', '/B/', '/C/'), 'D', $e);
preg_replace(array(1 => '/A2/', 'b2' => '/B2/', 3 => '/C2/'), 'D2', $e);
preg_replace(array('/A3/' => 3, '/B3/' => 4, '/C3/' => 4), 'D2', $e);

preg_replace_callback(array('/A/', '/B/', '/C/'), 'D', $e);
preg_replace_callback(array(1 => '/A21/', 'b21' => '/B21/', 3 => '/C21/'), 'D2', $e);
preg_replace_callback(array('/A32/' => 3, '/B32/' => 4, '/C32/' => 4), 'D2', $e);

preg_replace_callback_array(array('/A/', '/B/', '/C/'), 'D', $e);
preg_replace_callback_array(array(1 => '/A22/', 'b22' => '/B22/', 3 => '/C22/'), 'D2', $e);
preg_replace_callback_array(array('/A33/' => 3, '/B33/' => 4, '/C33/' => 4), 'D23', $e);


?>