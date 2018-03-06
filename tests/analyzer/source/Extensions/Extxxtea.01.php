<?php
$str = "Hello World! 你好，中国🇨🇳！";
$key = "1234567890";
$base64 = "D4t0rVXUDl3bnWdERhqJmFIanfn/6zAxAY9jD6n9MSMQNoD8TOS4rHHcGuE=";
$encrypt_data = xxtea_encrypt($str, $key);
$decrypt_data = xxtea_decrypt($encrypt_data, $key);
if ($str == $decrypt_data && base64_encode($encrypt_data) == $base64) {
    echo "success!";
} else {
    echo base64_encode($encrypt_data);
    echo "fail!";
}

echo xxtea_unserialize($encrypt_data);
?>