<?php

// Any input, in fact
filter_input($_env);
filter_input_var(INPUT_GET, 'b');
filter_input_array(INPUT_GET, 'i');


// OK, not a global
filter_var($_env);

filter_var($_GET);
filter_var_array($_POST['x']);
?>