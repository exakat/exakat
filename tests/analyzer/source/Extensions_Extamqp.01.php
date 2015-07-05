<?php

/* Create a new connection */
$cnn = new AMQPConnection();

// set the login details
$cnn->setLogin('mylogin');
$cnn->setPassword('mypass');

if ($cnn->connect()) {
    echo "Established a connection to the broker";
}
else {
    echo "Cannot connect to the broker";
}


?>