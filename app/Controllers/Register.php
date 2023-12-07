<?php

namespace App\Controllers;

use App\Models\User_model;

class Register extends BaseController
{
    public function index($pesan = null)
    {
        $data["pesan"] = $pesan;
        return view('Register/index', $data);
    }
    public function daftar()
    {
        $user = new User_model();
        $validationRules = [
            'username' => 'required|is_unique[user.username]',
            'nama' => 'required|is_unique[user.nama]',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($validationRules)) {
            $data["pesan"] = \Config\Services::validation()->listErrors();
            return view('Register/index', $data);
        }

        // Ambil data dari form pendaftaran
        $username = $this->request->getPost('username');
        $nama = $this->request->getPost('nama');
        $password = $this->request->getPost('password');

        // Simpan data pengguna ke database
        $data = [
            'username' => $username,
            'password' => md5($password),
            // Contoh enkripsi sederhana, sebaiknya gunakan enkripsi yang lebih aman
            'nama' => $nama,
            'role' => 'admin',
            // Atur peran sesuai kebutuhan
        ];

        $user->insert($data);

        // Redirect pengguna ke halaman login atau halaman lain yang sesuai
        return redirect()->to(base_url("public/Home"));
    }
}
