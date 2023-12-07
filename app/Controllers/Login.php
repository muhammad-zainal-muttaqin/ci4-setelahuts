<?php

namespace App\Controllers;

use App\Models\User_model;

class Login extends BaseController
{
    public function index($pesan = null)
    {
        $data["pesan"] = $pesan;
        //return view('Login/index', $data);
        return view('Login/index', $data);
    }

    public function cek()
    {
        //print_r($_POST);
        $username = $this->request->getPost('email-username');
        $pass = $this->request->getPost('password');

        $user = new User_model();
        $dataUser = $user->find($username);
        if ($dataUser == NULL) {
            $data["pesan"] = "Username tidak ditemukan.";
            return view('Login/index', $data);
        } else {
            if (md5($pass) == $dataUser->password) {
                //login berhasil
                $session = session();
                $session_data = [
                    "username" => $dataUser->username,
                    "role" => $dataUser->role
                ];
                $session->set($session_data);
                return redirect()->to(base_url("public/Home"));
            } else {
                $data["pesan"] = "Password Salah!";
                return view('Login/index', $data);
            }
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url("public/Login"));
    }
}