<?php
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

$v = new Zend\Validator\Isbn\Isbn10();
$v = new Zend\Validator\Module();
$v = new Zend\Validator\Uuid();
$v = new Zend\Validator\NotZend();
?>