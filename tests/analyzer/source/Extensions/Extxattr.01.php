<?php
$file = 'my_favourite_song.wav';
xattr_set($file, 'Artist', 'Someone');
xattr_set($file, 'My ranking', 'Good');
xattr_set($file, 'Listen count', '34');
xattr_met($file, 'typo in functionname', '34');

/* ... other code ... */

printf('You\'ve played this song %d times', xattr_get($file, 'Listen count')); 
?>