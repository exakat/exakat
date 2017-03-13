<?php
// introduced in 2.5
use Zend\Validator\EmailAddress;

$validator = new EmailAddress();

if ($validator->isValid($email)) {
    // email appears to be valid
} else {
    // email is invalid; print the reasons
    foreach ($validator->getMessages() as $messageId => $message) {
        printf("Validation failure '%s': %s\n", $messageId, $message);
    }
}

// introduced in 2.6
$v = new Zend\Validator\Isbn\Isbn10();
// introduced in 2.7
$v = new Zend\Validator\Module();
// introduced in 2.8
$v = new Zend\Validator\Uuid();


// never introduced 
$v = new Zend\Validator\NotZend();
?>