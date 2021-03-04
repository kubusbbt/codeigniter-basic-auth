<?php

namespace Libraries\Auth;

use CodeIgniter\Controller;
use CodeIgniter\Services;
use \Firebase\JWT\JWT;
use CodeIgniter\RESTful\ResourceController;

class JwtAuth extends Controller
{
    protected $response;

    public function __construct()
    {
        $this->session = session();
        $this->response = Services::response();
    }

    public function user()
    {
        var_dump('TODO - tutaj dekodowanie tokenu');
    }

    public function verify_token($token)
    {
        $key = config('Auth')->publicKey();
        try {
            $decoded = JWT::decode($token, $key, array('RS256'));
            return $decoded->data;
        } catch( \Exception $e) {
            return false;
        }
    }

    public static function verify()
    {
        if( isset($_SERVER['HTTP_AUTHORIZATION']) ){
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
            $arr = explode(" ", $authHeader);
            $token = $arr[1];

            $_instance = new self;

            if($token && $_instance->verify_token($token)) {
                return $_instance->verify_token($token);
            }
        }

        $response = Services::response();
        $response->setStatusCode(401)->send();
        die;
    }

    public function login()
    {
        $request = $this->request->getJSON();

        $login = $request->login;
        $password = $request->password;

        $config = config('Auth');
        $users = $config->users;

        return $this->loginWithToken($login, $password, $users);
    }

    private function loginWithToken($login, $password, $users)
    {
        if( !isset($users[$login]) || !password_verify($password, $users[$login]) ){
            $output = [
                'status' => 401,
                'message' => 'login or password is incorrect',
            ];
            return $this->response->setStatusCode(401)->setJSON($output);
        }

        $key = config('Auth')->privateKey();
        $issue_date_claim = time();
        $not_before_claim = $issue_date_claim + 10;
        $expire_claim = $issue_date_claim + 3600;

        $payload = array(
            "iss" => "THE_CLAIM",
            "aud" => "THE_AUDIENCE",
            "iat" => $issue_date_claim,
            "nbf" => $not_before_claim,
            "exp" => $expire_claim,
            "data" => array(
                "user" => $login,
            )
        );

        $token = JWT::encode($payload, $key, 'RS256');

        $output = [
            'status' => 200,
            'message' => 'login successful',
            "token" => $token,
            "email" => $login,
            "expireAt" => $expire_claim
        ];

        return $this->response->setStatusCode(200)->setJSON($output);
    }

    private function hash($password)
    {
        die( password_hash($password, PASSWORD_DEFAULT) );
    }
}
