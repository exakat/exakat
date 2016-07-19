<?php

echo $_GET['incoming'];
print array_sum($_POST);

custom_function($_SERVER);
custom_function2($_ENV['2']);

move_uploaded_file($_FILES['name']['filename'], $_FILES['name']['destination']);

move_uploaded_file($_FILES['name']['filename'], 1);
move_uploaded_file(0, $_FILES['name']['filename']);

foreach ($_FILES["pictures"]["error"] as $key => $error) {
    if ($error == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["pictures"]["tmp_name"][$key];
        $name = $_FILES["pictures"]["name"][$key];
        move_uploaded_file($tmp_name, "$uploads_dir/$name");
    }
}

echo "{$_COOKIE['incoming']}";
print "A".$_SERVER['incoming']."B";
print("A".$_COOKIE['incoming']['array']."B");
echo "{$_ENV['incoming0']}","{$_ENV['incoming1']}","{$_ENV['incoming2']}";

echo '{$_ENV["incoming0"]}';

$s = strtolower($_GET['variable']);

?>