<?php
$hashed_password = crypt('mypassword'); // let the salt be automatically generated

if (hash_equals($hashed_password, crypt($user_input, $hashed_password))) {
   echo "Password verified!";
}
?>