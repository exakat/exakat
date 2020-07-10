<?php

set_error_handler(function ($errno, $message) {
    throw new Exception("Custom error handler: $message");
});

echo match ($undefVar) {
   default => "This should not get printed with or without opcache\n",
   1, 2, 3, 4, 5 => "Also should not be printed\n",
};

echo "unreachable\n";
