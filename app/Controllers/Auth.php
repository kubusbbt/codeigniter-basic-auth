<?php

namespace App\Controllers;

use Libraries\Auth\JwtAuth;
use Libraries\Auth\SessionAuth;

class Auth extends JwtAuth
{
    public function index()
    {
        var_dump( session()->get('error') );
        return '<form action="/auth/login/" method="post"><input type="text" name="login"><input type="password" name="password"><button>Zaloguj</button></form>';
    }

    public function hash()
    {
        $password = 'password';
        die( password_hash($password, PASSWORD_DEFAULT) );
    }
}