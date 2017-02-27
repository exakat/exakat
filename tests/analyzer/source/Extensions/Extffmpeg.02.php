<?php

$movie = new ffmpeg_movie($path_to_media, $persistent);
echo 'The movie lasts '.$movie->getDuration().' seconds';

// This is from another extension
ffmpeg\ffmpeg::create();

?>