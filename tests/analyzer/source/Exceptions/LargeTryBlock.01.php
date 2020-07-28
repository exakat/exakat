<?php

// try is one expression only
try {
    $database->query($query);
} catch (DatabaseException1 $e) {
    // process exception
}

// Too many expressions around the one that may actually emit the exception
try {
    $SQL = build_query($arguments);
    $database = new Database($dsn);
    $database->setOption($options);
    $statement = $database->prepareQuery($SQL);
    $result = $statement->query($query);
} catch (DatabaseException5 $e) {
    // process exception
}

// Too many expressions around the one that may actually emit the exception
try {
    ++$a6;
    $SQL = build_query($arguments);
    $database = new Database($dsn);
    $database->setOption($options);
    $statement = $database->prepareQuery($SQL);
    $result = $statement->query($query);
} catch (DatabaseException6 $e) {
    // process exception
}

?>