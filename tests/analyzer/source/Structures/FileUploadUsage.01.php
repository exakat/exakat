<?php
$uploaddir = '/var/www/uploads/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "A";
} else {
    echo "B";
}

print_r($_FILES);

$o->move_uploaded_file($method);
Classe::move_uploaded_file($staticmethod);
?>