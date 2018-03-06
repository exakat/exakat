<?php
//Instantiate a new Gmagick object
$image = new Gmagick('example.jpg');

//Make thumbnail from image loaded. 0 for either axes preserves aspect ratio
$image->thumbnailImage(100, 0);

//Create a border around the image, then simulate how the image will look like as an oil painting
//Notice the chaining of mutator methods which is supported in gmagick
$image->borderImage("yellow", 8, 8)->oilPaintImage(0.3);

//Write the current image at the current state to a file
$image->write('example_thumbnail.jpg');

gmagick('Functioncall');
?>