<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class Home extends BaseController
{
    protected $auth_protected = ['secret', 'index'];

    public function index()
	{
		return view('welcome_message');
	}

	public function secret()
    {
        echo 'tylko dla zalogowanych';
    }
}
