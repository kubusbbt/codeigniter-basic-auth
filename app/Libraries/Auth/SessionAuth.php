<?php

namespace Libraries\Auth;

use App\Controllers\BaseController;
use CodeIgniter\Services;

class SessionAuth extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }

    public function user()
    {
        return $this->session->get('user');
    }

    public static function verify()
    {
        $user = session()->get('user');

        if(!$user) {
            header("Location:" . base_url() . '/auth');
            die;
        }
        return true;
    }

    public function login()
    {
        $login = $this->request->getPost('login');
        $password = $this->request->getPost('password');

        $config = config('Auth');
        $users = $config->users;

        return $this->loginWithSession($login, $password, $users);
    }

    private function loginWithSession($login, $password, $users)
    {
        if( isset($users[$login]) && password_verify($password, $users[$login]) ){
            $this->session->set('user', $login);
            return redirect()->to('/auth/user/');
        }
        return redirect()->back()->with('error', 'Błędny login lub hasło');
    }
}