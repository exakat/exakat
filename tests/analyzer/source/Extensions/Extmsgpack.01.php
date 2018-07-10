<?php

    $serialized = msgpack_serialize($variable);
    $unserialized = msgpack_unserialize($serialized);
    $error = msgpack_deserialize($serialized);
?>