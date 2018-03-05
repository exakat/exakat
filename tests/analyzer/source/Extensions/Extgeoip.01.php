<?php
    $continent = geoip_continent_code_by_name('www.example.com');
    if ($continent) {
        echo 'Cet hôte est situé en : ' . $continent;
    }

    $continent = new geoip('Not geoip');
?>