<?php

    // OK
    C::import_request_variables($a->b()->c());
    
    $c->import_request_variables($_POST);

    // KO
    import_request_variables($a->b()->c());

    \import_request_variables($_GET);

?>
?>