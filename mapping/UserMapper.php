<?php

class UserMapper {

    public static function map(User $user, array $properties) {

        if (array_key_exists('id', $properties)) {
            $user->setId($properties['id']);
        }
        if (array_key_exists('first_name', $properties)) {
            $user->setFirstName($properties['first_name']);
        }
        if (array_key_exists('last_name', $properties)) {
            $user->setLastName($properties['last_name']);
        }
        if (array_key_exists('username', $properties)) {
            $user->setUsername($properties['username']);
        }
        if (array_key_exists('password', $properties)) {
            $user->setPassword($properties['password']);
        }
        if (array_key_exists('status', $properties)) {
            $user->setStatus($properties['status']);
        }
    }

}
