<?php

class PasswordHasher {
    private static function randomStr($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    static function hashPassword($username, $password, $salt = '') {
      return hash('sha256', $username.$password.$salt);
    }

    static function generateSalt($length = 8) {
      return PasswordHasher::randomStr(8);
    }
    

}

?>