<?php

echo password_hash("password", PASSWORD_DEFAULT);
echo password_hash("password", PASSWORD_ARGON2I);
echo password_hash("password", \PASSWORD_ARGON2I);
echo password_hash("password", PASSWORD_BCRYPT);

echo password_argon2i("password", PASSWORD_BCRYPT);


?>