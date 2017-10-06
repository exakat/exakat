<?php

$extension = strtolower( substr( strrchr($_FILES['upload']['name'], ".") ,1) );
if(move_uploaded_file($_FILES['upload1']['tmp_name'], "unsafe/".$id.".$extension")) {
}

if(move_uploaded_file($_FILES['upload2']['tmp_name'], "safe/".$id.'.some_extension')) { }
?>