<?php

copy($tmp . $_FILES[$fieldname]["name"], $uploaddir . 'b.ico');
copy($file['tmp_name'], $target);
rename($file['tmp_name'], $target);
copy($this->file['tmp_name'], $target);
rename($this->file['tmp_name'], $target);
move_uploaded_files($_FILES['tmp_name'], $target);


?>