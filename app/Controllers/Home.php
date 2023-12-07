<?php

namespace App\Controllers;

use App\Libraries\DataTables;
use App\Models\User_model;

class Home extends BaseController
{
    public function __construct()
    {
        $this->DataTables = new DataTables();
    }

    public function index($pesan = null)
    {
        $data["pesan"] = $pesan;

        $session = session();

        if ($session->get('role') == null) {
            return redirect()->to(base_url('public/Login'));
        }

        return view('Home/index', $data);
    }

    public function grid()
    {
        $query = "SELECT * FROM user";

        $where = null;

        $isWhere = null;

        $search = array('username', 'password', 'nama', 'role');
        echo $this->DataTables->BuildDatatables($query, $where, $isWhere, $search);
    }

    public function simpan()
    {
        $username = $this->request->getPost("username");
        $password = $this->request->getPost("password");
        $nama = $this->request->getPost("nama");
        $role = $this->request->getPost("role");

        $user = new User_model();
        $dataUser = $user->find($username);
        if ($dataUser == NULL) {
            //boleh simpan
            $dataInsert = array(
                "username" => $username,
                "password" => md5($password),
                "nama" => $nama,
                "role" => $role,
            );
            $user->insert($dataInsert);
            echo "success";
        } else {
            //tidak boleh simpan
            echo "failed";
        }
    }

    public function delete()
    {
        $id = $this->request->getPost("id");

        $user = new User_model();
        $dataUser = $user->find($id);
        if ($dataUser == NULL) {
            //tidak boleh dihapus
            echo "failed";
        } else {
            //boleh dihapus
            $user->delete($id);
            echo "success";
        }
    }

    public function edit()
    {
        $oldUsername = $this->request->getPost("oldUsername");
        $newUsername = $this->request->getPost("newName");
        $newPassword = $this->request->getPost("newPassword");

        $userModel = new User_model();
        $userData = $userModel->find($oldUsername);

        if ($userData) {
            // Data ditemukan, lakukan proses edit
            $updateData = array();

            if ($newUsername) {
                $updateData['username'] = $newUsername;
            }

            if ($newPassword) {
                $updateData['password'] = md5($newPassword);
            }

            // Tambahkan logika update sesuai kebutuhan Anda
            $userModel->update($oldUsername, $updateData);

            echo "success";
        } else {
            // Data tidak ditemukan
            echo "failed";
        }
    }

    // public function sidebar($pesan = null)
    // {
    //     $data["pesan"] = $pesan;

    //     $session = session();

    //     if ($session->get('role') == null) {
    //         return redirect()->to(base_url('public/Login'));
    //     }

    //     return view('Home/sidebar', $data);
    // }

    // public function content($pesan = null)
    // {
    //     $data["pesan"] = $pesan;

    //     $session = session();

    //     if ($session->get('role') == null) {
    //         return redirect()->to(base_url('public/Login'));
    //     }

    //     return view('Home/content', $data);
    // }

    // public function header($pesan = null)
    // {
    //     $data["pesan"] = $pesan;

    //     $session = session();

    //     if ($session->get('role') == null) {
    //         return redirect()->to(base_url('public/Login'));
    //     }

    //     return view('Home/header', $data);
    // }
}
