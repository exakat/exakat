<?php

// quite a fishy class
abstract class User {
    public abstract function findByEmail($email);
    public abstract function findByUsername($username);
    public abstract function findByFirstName($firstname);
    public abstract function findByLastName($lastname);
    public abstract function findByName($name);
    public abstract function findById($id);

    public abstract function insert($user);
    public abstract function update($user);
}

abstract class UserOK {
    public abstract function findByEmail($email);
    public abstract function findByUsername($username);

    public abstract function insert($user);
    public abstract function update($user);
}

?>